<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\listener;

use Ds\Set;
use jasonwynn10\VeinMiner\api\VeinMinerManager;
use jasonwynn10\VeinMiner\data\PlayerPreferences;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use jasonwynn10\VeinMiner\utils\ItemValidator;
use jasonwynn10\VeinMiner\utils\VMConstants;
use jasonwynn10\VeinMiner\utils\VMEventFactory;
use jasonwynn10\VeinMiner\VeinMiner;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\entity\animation\ArmSwingAnimation;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\item\Durable;
use pocketmine\item\Tool;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\ItemBreakSound;

final class BreakBlockListener implements Listener{

	private VeinMinerManager $manager;

	public function __construct(private VeinMiner $plugin){
		$this->manager = $plugin->getVeinMinerManager();
	}

	public function onBlockBreak(BlockBreakEvent $event) : void {
		$origin = $event->getBlock();

		if($this->manager->getBlockToBeVeinMinedOrigin($origin) !== null) {
			if($this->plugin->getConfig()->get(VMConstants::CONFIG_COLLECT_ITEMS_AT_SOURCE, true) === true)
				$event->setDrops([]);
			return;
		}

		$player = $event->getPlayer();
		$item = $event->getItem();

		[$category, $toolTemplate] = ToolCategory::getWithTemplate($item);
		if ($category === null || ($category !== ToolCategory::$HAND && $toolTemplate === null)) {
			return;
		}

		// Invalid player state check
		$playerData = PlayerPreferences::get($player);
		$activation = $playerData->getActivationStrategy();
		$algorithmConfig = $toolTemplate !== null ? $toolTemplate->getConfig() : $category->getConfig();
		if (!$activation->isValid($player)
			|| !$category->hasPermission($player)
			|| $this->manager->isDisabledGameMode($player->getGameMode())
			|| $playerData->isVeinMinerDisabled($category)
			|| $algorithmConfig->isDisabledWorld($origin->getPosition()->getWorld())
			|| !ItemValidator::isValid($item, $category)) {
			return;
		}

		$originBlockData = $origin->getIdInfo();
		if(!$this->manager->isVeinMineable($originBlockData, $category)){
			return;
		}

		// Economy check
		$economy = $this->plugin->getEconomyModifier();
		if(!$economy->shouldCharge($player, $algorithmConfig)) { // attempt charge first then handle fail case
			if(!$economy->hasSufficientBalance($player, $algorithmConfig)) {
				$player->sendMessage(TextFormat::GRAY . 'You have insufficient funds to vein mine (Required: ' . TextFormat::YELLOW . $algorithmConfig->getCost() . TextFormat::GRAY . ')');
				return;
			}
			$economy->charge($player, $algorithmConfig);
		}

		// TIME TO VEINMINE
		$blocks = new Set($origin);
		$originVeinBlock = $this->manager->getVeinBlockFromBlockList($originBlockData, $category);
		if($originVeinBlock === null){
			return;
		}

		$pattern = $this->plugin->getVeinMiningPattern();
		$pattern->allocateBlocks($blocks, $originVeinBlock, $origin, $category, $toolTemplate, $algorithmConfig, $this->manager->getAliasFor($origin));
		$blocks = $blocks->filter(function(Block $block) use ($player, $category, $toolTemplate, $algorithmConfig) : bool {
			return !$block instanceof Air;
		});
		if(count($blocks) < 1){
			return;
		}

		// Fire a new PlayerVeinMineEvent
		$veinmineEvent = VMEventFactory::callPlayerVeinMineEvent($player, $originVeinBlock, $item, $category, $blocks, $pattern);
		if(!$veinmineEvent->isCancelled() || $blocks->count() < 1){
			return;
		}

		// Apply metadata to all blocks to be vein mined and all other relevant objects/entities
		$this->manager->setPlayerVeinMining($player);
		foreach($blocks as $block) {
			$this->manager->setBlockToBeVeinMined($block, $origin);
		}

		// Actually destroying the allocated blocks
		$maxDurability = $item instanceof Tool ? ($item->getMaxDurability() - ($this->plugin->getConfig()->get(VMConstants::CONFIG_REPAIR_FRIENDLY_VEINMINER, false) ? 1 : 0)) : 0;
		$hungerModifier = max(0, $this->plugin->getConfig()->get(VMConstants::CONFIG_HUNGER_HUNGER_MODIFIER, 0)) * 0.025;
		$minimumFoodLevel = max(0, $this->plugin->getConfig()->get(VMConstants::CONFIG_HUNGER_MINIMUM_FOOD_LEVEL, 0));

		$hungryMessage = $this->plugin->getConfig()->get(VMConstants::CONFIG_HUNGER_HUNGRY_MESSAGE, '');
		if($hungryMessage !== ''){
			$hungryMessage = TextFormat::colorize($hungryMessage);
		}

		$drops = [];
		foreach($blocks as $block){
			$drops += $block->getDrops($event->getItem());
			//apply hunger
			if($hungerModifier > 0 && !$player->hasPermission(VMConstants::PERMISSION_FREE_HUNGER)) {
				$this->applyHungerDebuff($player, $hungerModifier);

				if($player->getHungerManager()->getFood() <= $minimumFoodLevel){
					if($hungryMessage !== ''){
						$player->sendMessage($hungryMessage);
					}

					break;
				}
			}

			//check for tool damage
			if($maxDurability > 0 && $category != ToolCategory::$HAND) {
				if($item->isNull()) {
					break;
				}

				$meta = $item->getDamage();
				if($meta >= $maxDurability) {
					break;
				}
			}

			// break the block
			if($block !== $origin) {
				$this->breakBlock($player, $block);
			}
		}
		if($this->plugin->getConfig()->get(VMConstants::CONFIG_COLLECT_ITEMS_AT_SOURCE, true) === true)
			$event->setDropsVariadic(...array_merge($event->getDrops(), $drops));

		// Remove applied metadata
		$this->manager->removePlayerVeinMining($player);
		foreach($blocks as $block) {
			$this->manager->removeBlockToBeVeinMined($block);
		}

		// VEINMINE - DONE
	}

	private function breakBlock(Player $player, Block $block): bool {
		$pos = $block->getPosition();

		$player->removeCurrentWindow();

		$player->broadcastAnimation(new ArmSwingAnimation($player), $player->getViewers());
		$player->stopBreakBlock($pos);
		$item = $player->getInventory()->getItemInHand();
		$oldItem = clone $item;
		if($player->getWorld()->useBreakOn($pos, $item, $player, true)){
			if($player->hasFiniteResources() && !$item->equalsExact($oldItem) && $oldItem->equalsExact($player->getInventory()->getItemInHand())){
				if($item instanceof Durable && $item->isBroken()){
					$player->broadcastSound(new ItemBreakSound());
				}
				$player->getInventory()->setItemInHand($item);
			}
			$player->getHungerManager()->exhaust(0.005, PlayerExhaustEvent::CAUSE_MINING);
			return true;
		}

		return false;
	}

	private function applyHungerDebuff(Player $player, float $hungerModifier): void{
		$foodLevel = $player->getHungerManager()->getFood();
		$saturation = $player->getHungerManager()->getSaturation();
		$exhaustion = $player->getHungerManager()->getExhaustion();

		$exhaustion += $hungerModifier;
		$exhaustion %= 4;
		$saturation -= (int)(($exhaustion + $hungerModifier) / 4);

		if($saturation < 0){
			$foodLevel += $saturation;
			$saturation = 0;
		}

		$player->getHungerManager()->setFood($foodLevel);
		$player->getHungerManager()->setSaturation($saturation);
		$player->getHungerManager()->setExhaustion($exhaustion);
	}

}