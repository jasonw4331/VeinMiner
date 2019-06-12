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
	/** @var Config $blockList **/
	private $blockList;
	/** @var Config $toggleSettings */
	private $toggleSettings;
	/** @var bool[] $inUse */
	private $inUse;

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$blocksByTool = [];
		/** @var Block $block */
		foreach(BlockFactory::getBlockStatesArray() as $block) {
			if(in_array($block->getName(), ["update!", "ate!upd", "reserved6", "", "Air", "Unknown", " Wooden Slab", "Upper  Wooden Slab"]))
				continue;
			//echo $block->getName()."\n";
			$tool = $this->getToolTypeString($block->getToolType());
			$state = false;
			if($block instanceof Obsidian or strpos(strtolower($block->getName()), "ore") or $block instanceof Wood or $block instanceof Wood2 or $block instanceof Leaves or $block instanceof Leaves2) {
				$state = true;
			}
			$blocksByTool[$tool][$block->getName()] = $state;
		}
		$this->blockList = new Config($this->getDataFolder()."blocklist.yml", Config::YAML, $blocksByTool);
		$this->toggleSettings = new Config($this->getDataFolder()."toggles.json", Config::JSON);
	}

	/**
	 * @param CommandSender $sender
	 * @param Command $command
	 * @param string $label
	 * @param array $args
	 *
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		if(empty($args))
			return false;
		switch($args[0]) {
			case "toggle":
				if($sender->hasPermission("veinminer.command.toggle") and $sender->hasPermission("veinminer.use")) {
					$newState = !$this->toggleSettings->getNested($sender->getName().".state", true);
					$this->toggleSettings->setNested($sender->getName().".state", $newState);
					$this->toggleSettings->save();
					$sender->sendMessage("VeinMiner is now ". ($newState ? "on" : "off"));
					return true;
				}
			break;
			case "mode":
				if($sender->hasPermission("veinminer.command.mode") and $sender->hasPermission("veinminer.use")) {
					if(empty($args[1]) or !in_array(strtolower($args[1]), ["sneak", "stand", "always"])) {
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
					if(empty($args[1]) or !in_array(strtolower($args[1]), ["add", "remove", "list"])) {
						$sender->sendMessage($this->getServer()->getLanguage()->translateString("commands.generic.usage", ["/vm blocklist <add|remove|list> [args]"]));
						return true;
					}
					switch(strtolower($args[1])) {
						case "add":
							if($sender->hasPermission("veinminer.command.blocklist.add")) {
								if(empty($args[2])) {
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
								if(empty($args[2])) {
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
								if(!empty($args[3]) and is_numeric($args[3])) {
									$pageNumber = (int) $args[3];
									if ($pageNumber <= 0) {
										$pageNumber = 1;
									}
								}elseif(!empty($args[3])){
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
								$pageNumber = (int) min(count($allBlocks), $pageNumber);
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

	public function onBreak(BlockBreakEvent $event) {
		if(isset($this->inUse[$event->getPlayer()->getName()]))
			return;
		if(!$event->getPlayer()->hasPermission("veinminer.use")) {
			return;
		}
		$player = $event->getPlayer();
		if(!$this->toggleSettings->getNested($player->getName().".state", true)) {
			return;
		}
		$block = $event->getBlock();
		if(!$this->blockList->getNested($this->getToolTypeString($block->getToolType()).".".$block->getName(), false))
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
	public function veinMine(Block $block, Item $item, Player $player, array &$ignore = []) {
		if($block->isValid()) {
			$ignore[] = $block->asVector3()->__toString();
			foreach($block->getAllSides() as $side) {
				if($side->getName() === $block->getName() and !in_array($side->asVector3()->__toString(), $ignore)) {
					//echo $side->getName()." found\n";
					$this->veinMine($side, $item, $player, $ignore);
				}
			}
			$block->getLevel()->useBreakOn($block, $item, $player, true);
		}
	}

	/**
	 * @param int $type
	 *
	 * @return string
	 */
	public function getToolTypeString(int $type) : string {
		switch($type) {
			case BlockToolType::TYPE_NONE:
				$tool = "Hand";
			break;
			case BlockToolType::TYPE_SWORD:
				$tool = "Sword";
			break;
			case BlockToolType::TYPE_SHOVEL:
				$tool = "Shovel";
			break;
			case BlockToolType::TYPE_PICKAXE:
				$tool = "Pickaxe";
			break;
			case BlockToolType::TYPE_AXE:
				$tool = "Axe";
			break;
			case BlockToolType::TYPE_SHEARS:
				$tool = "Shears";
			break;
			case BlockToolType::TYPE_SWORD | BlockToolType::TYPE_SHEARS:
				$tool = "Sword";
			break;
			default:
				throw new \UnexpectedValueException();
		}
		return $tool;
	}
}