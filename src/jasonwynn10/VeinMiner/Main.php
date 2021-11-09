<?php
declare(strict_types=1);
namespace jasonwynn10\VeinMiner;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockToolType;
use pocketmine\block\Leaves;
use pocketmine\block\Leaves2;
use pocketmine\block\Obsidian;
use pocketmine\block\Wood;
use pocketmine\block\Wood2;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {
	private Config $blockList;
	private Config $toggleSettings;
	/** @var bool[] $inUse */
	private array $inUse;

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$blocksByTool = [];
		/** @var Block $block */
		foreach(BlockFactory::getBlockStatesArray() as $block) {
			if(in_array($block->getName(), ["update!", "ate!upd", "reserved6", "", "Air", "Unknown", " Wooden Slab", "Upper  Wooden Slab"], true))
				continue;
			//echo $block->getName()."\n";
			$tool = $this->getToolTypeString($block->getToolType());
			$state = false;
			if($block instanceof Obsidian or str_contains($block->getName(), "ore") or $block instanceof Wood or $block instanceof Wood2 or $block instanceof Leaves or $block instanceof Leaves2) {
				$state = true;
			}
			$blocksByTool[$tool][$block->getName()] = $state;
		}
		$this->blockList = new Config($this->getDataFolder()."blocklist.yml", Config::YAML, $blocksByTool);
		$this->toggleSettings = new Config($this->getDataFolder()."toggles.json", Config::JSON);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		if(count($args) < 1)
			return false;
		switch($args[0]) {
			case "toggle":
				if($sender->hasPermission("veinminer.command.toggle") and $sender->hasPermission("veinminer.use")) {
					$newState = !(bool)$this->toggleSettings->getNested($sender->getName().".state", true);
					$this->toggleSettings->setNested($sender->getName().".state", $newState);
					$this->toggleSettings->save();
					$sender->sendMessage("VeinMiner is now ". ($newState ? "on" : "off"));
					return true;
				}
			break;
			case "mode":
				if($sender->hasPermission("veinminer.command.mode") and $sender->hasPermission("veinminer.use")) {
					if(!isset($args[1]) or !in_array(strtolower($args[1]), ["sneak", "stand", "always"], true)) {
						$sender->sendMessage($this->getServer()->getLanguage()->translateString("commands.generic.usage", ["/vm mode <sneak|stand|always>"]));
						return true;
					}
					$this->toggleSettings->setNested($sender->getName().".mode", strtolower($args[1]));
					$this->toggleSettings->save();
					$sender->sendMessage("VeinMining mode is now set to ". strtolower($args[1]));
					return true;
				}
			break;
			case "blocklist":
				if($sender->hasPermission("veinminer.command.blocklist")) {
					if(!isset($args[1]) or !in_array(strtolower($args[1]), ["add", "remove", "list"], true)) {
						$sender->sendMessage($this->getServer()->getLanguage()->translateString("commands.generic.usage", ["/vm blocklist <add|remove|list> [args]"]));
						return true;
					}
					switch(strtolower($args[1])) {
						case "add":
							if($sender->hasPermission("veinminer.command.blocklist.add")) {
								if(!isset($args[2])) {
									$sender->sendMessage($this->getServer()->getLanguage()->translateString("commands.generic.usage", ["/vm blocklist add <id: string>"]));
									return true;
								}
								$blockArr = explode(":", $args[2]);
								$block = BlockFactory::get((int)$blockArr[0], (int)($blockArr[1] ?? 0));
								$tool = $this->getToolTypeString($block->getToolType());
								$this->blockList->setNested($tool.".".$block->getName(), true);
								$this->blockList->save();
								$sender->sendMessage($block->getName()." has been enabled in the blocklist");
								return true;
							}
						break;
						case "remove":
							if($sender->hasPermission("veinminer.command.blocklist.remove")) {
								if(!isset($args[2])) {
									$sender->sendMessage($this->getServer()->getLanguage()->translateString("commands.generic.usage", ["/vm blocklist remove <id: string>"]));
									return true;
								}
								$blockArr = explode(":", $args[2]);
								$block = BlockFactory::get((int)$blockArr[0], (int)($blockArr[1] ?? 0));
								$tool = $this->getToolTypeString($block->getToolType());
								$this->blockList->setNested($tool.".".$block->getName(), false);
								$this->blockList->save();
								$sender->sendMessage($block->getName()." has been disabled in the blocklist");
								return true;
							}
						break;
						case "list":
							if($sender->hasPermission("veinminer.command.blocklist.list")) {
								$pageNumber = 1;
								if(isset($args[3]) and is_numeric($args[3])) {
									$pageNumber = (int) $args[3];
									if ($pageNumber <= 0) {
										$pageNumber = 1;
									}
								}elseif(isset($args[3])){
									return false;
								}
								/** @var bool[] $allBlocks */
								$allBlocks = [];
								foreach($this->blockList->getAll() as $tool => $blockArray) {
									$allBlocks = array_merge($allBlocks, $blockArray);
								}
								ksort($allBlocks, SORT_NATURAL | SORT_FLAG_CASE);
								/** @var bool[][] $allBlocks */
								$allBlocks = array_chunk($allBlocks, $sender->getScreenLineHeight(), true);
								$pageNumber = min(count($allBlocks), $pageNumber);
								foreach($allBlocks[$pageNumber - 1] as $name => $state) {
									$sender->sendMessage($name . ": " . ($state ? "on" : "off"));
								}
								return true;
							}
						break;
						default:
							$sender->sendMessage($this->getServer()->getLanguage()->translateString("commands.generic.usage", ["/vm blocklist <add|remove|list> [args]"]));
							return true;
					}
				}
			break;
			default:
				return false;
		}
		$sender->sendMessage($this->getServer()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.permission"));
		return true;
	}

	public function onBreak(BlockBreakEvent $event) : void {
		if(isset($this->inUse[$event->getPlayer()->getName()]))
			return;
		if(!$event->getPlayer()->hasPermission("veinminer.use")) {
			return;
		}
		$player = $event->getPlayer();
		if(!(bool)$this->toggleSettings->getNested($player->getName().".state", true)) {
			return;
		}
		$block = $event->getBlock();
		if(!(bool)$this->blockList->getNested($this->getToolTypeString($block->getToolType()).".".$block->getName(), false))
			return;
		if($this->toggleSettings->getNested($player->getName().".mode", "sneak") === "always" or ($player->isSneaking() and $this->toggleSettings->getNested($player->getName().".mode", "sneak") === "sneak") or (!$player->isSneaking() and $this->toggleSettings->getNested($player->getName().".mode", "sneak") === "stand")) {
			$this->inUse[$player->getName()] = true;
			//echo "ran once\n";
			$this->veinMine($block, $event->getItem(), $player);
			unset($this->inUse[$player->getName()]);
		}
	}

	/**
	 * @param Block $block
	 * @param Item $item
	 * @param Player $player
	 * @param Block[] $ignore @internal
	 */
	public function veinMine(Block $block, Item $item, Player $player, array &$ignore = []) : void {
		if($block->isValid()) {
			$ignore[] = $block->asVector3()->__toString();
			foreach($block->getAllSides() as $side) {
				if($side->getName() === $block->getName() and !in_array($side->asVector3()->__toString(), $ignore, true)) {
					//echo $side->getName()." found\n";
					$this->veinMine($side, $item, $player, $ignore);
				}
			}
			$block->getLevelNonNull()->useBreakOn($block, $item, $player, true);
		}
	}

	public function getToolTypeString(int $type) : string {
		return match ($type) {
			BlockToolType::TYPE_NONE => "Hand",
			BlockToolType::TYPE_SWORD, BlockToolType::TYPE_SWORD | BlockToolType::TYPE_SHEARS => "Sword",
			BlockToolType::TYPE_SHOVEL => "Shovel",
			BlockToolType::TYPE_PICKAXE => "Pickaxe",
			BlockToolType::TYPE_AXE => "Axe",
			BlockToolType::TYPE_SHEARS => "Shears",
			default => throw new \UnexpectedValueException(),
		};
	}
}