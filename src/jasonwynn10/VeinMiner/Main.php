<?php
declare(strict_types=1);
namespace jasonwynn10\VeinMiner;

use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {

	public const CHAT_PREFIX = TextFormat::BLUE.TextFormat::BOLD."VeinMiner | ".TextFormat::GRAY;
	public const BLOCK_DATA_PATTERN = "(?:[\\w:]+)(?:\\[(.+=.+)+\\])*";
	/** @var self|null $instance */
	private static $instance;
	/** @var Config $playerData */
	private $playerData;
	/** @var array $toolCategories */
	private $toolCategories = ["sword,pickaxe,axe,shears,hoe,shovel"];
	/** @var string[] $globalList */
	private $globalList = [];
	/** @var string[][] $toolList */
	private $toolList = [];
	/** @var string[] $disabledWorlds */
	private $disabledWorlds = [];
	/** @var string[] $blockAliases */
	private $blockAliases = [];

	/**
	 * @return Main|null
	 */
	public static function getInstance() : ?Main {
		return self::$instance;
	}

	public function onLoad() {
		self::$instance = $this;
		$this->saveDefaultConfig();
		$this->playerData = new Config($this->getDataFolder()."playerData.json", Config::JSON);
	}

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->debug("Loading configuration options to local memory");
		$this->loadToolTemplates();
		$this->loadVeinableBlocks();
		$this->loadDisabledWorlds();
		$this->loadMaterialAliases();
	}

	public function onDisable() {
		$this->getLogger()->debug("Clearing localised data");
		$this->playerData = null;
		$this->patterRegistry->clearPatterns();
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
		if(empty($args)) {
			$sender->sendMessage(self::CHAT_PREFIX.TextFormat::RED."Invalid Command Syntax! ".TextFormat::GRAY."Missing Parameter. ".TextFormat::YELLOW."/veinminer <reload|version|blocklist|toggle|pattern>");
			return true;
		}
		switch(strtolower($args[0])) {
			case "reload":
				if(!$sender->hasPermission("veinminer.reload")) {
					$sender->sendMessage(self::CHAT_PREFIX.TextFormat::RED."You have insufficient permissions to execute this command");
					return true;
				}
				$this->reloadConfig();
				$this->loadToolTemplates();
				$this->loadVeinableBlocks();
				$this->loadDisabledWorlds();
				$this->loadMaterialAliases();
				$sender->sendMessage(self::CHAT_PREFIX.TextFormat::GREEN."VeinMiner configuration successfully reloaded");
			break;
			case "version":
				$sender->sendMessage(TextFormat::GOLD.TextFormat::BOLD.TextFormat::STRIKETHROUGH."--------------------------------------------");
				$sender->sendMessage("");
				$sender->sendMessage(TextFormat::DARK_AQUA.TextFormat::BOLD."Version: ".TextFormat::RESET.TextFormat::GRAY.$this->getDescription()->getVersion());
				$sender->sendMessage(TextFormat::DARK_AQUA.TextFormat::BOLD."Version: ".TextFormat::RESET.TextFormat::GRAY."jasonwynn10 ".TextFormat::YELLOW."( https://jasonwynn10.com )");
				$sender->sendMessage(TextFormat::DARK_AQUA.TextFormat::BOLD."Development Page: ".TextFormat::RESET.TextFormat::GRAY."https://poggit.pmmp.io/p/VeinMiner");
				$sender->sendMessage(TextFormat::DARK_AQUA.TextFormat::BOLD."Report Bugs to: ".TextFormat::RESET.TextFormat::GRAY."https://github.com/jasonwynn10/VeinMiner/issues");
				$sender->sendMessage("");
				$sender->sendMessage(TextFormat::GOLD.TextFormat::BOLD.TextFormat::STRIKETHROUGH."--------------------------------------------");
			break;
			case "toggle":
				if(!$sender instanceof Player) {
					$sender->sendMessage(self::CHAT_PREFIX.TextFormat::RED."You may not toggle veinminer from the console");
					return true;
				}
				if(!$this->canVeinMine($sender)) {
					$sender->sendMessage(self::CHAT_PREFIX.TextFormat::RED."You may not toggle a feature to which you do not have access");
					return true;
				}
				if(!$sender->hasPermission("veinminer.toggle")) {
					$sender->sendMessage(self::CHAT_PREFIX.TextFormat::RED."You have insufficient permissions to execute this command");
					return true;
				}
				if(isset($args[1])) {
					// TODO:
				}else{
					// TODO:
				}
			break;
			case "blocklist":
				if(!isset($args[1])) {
					$sender->sendMessage(self::CHAT_PREFIX.TextFormat::RED."Invalid Command Syntax! ".TextFormat::GRAY."Missing Parameter(s). ".TextFormat::YELLOW."/".$label." ".$args[0]." <tool>"."<add|remove|list>");
					return true;
				}
				// TODO:
			break;
			case "pattern":
				if(!$sender instanceof Player) {
					$sender->sendMessage("VeinMiner pattern cannot be changed from the console.");
					return true;
				}
				if(!$sender->hasPermission("veinminer.pattern")) {
					$sender->sendMessage(self::CHAT_PREFIX.TextFormat::RED."You have insufficient permissions to execute this command");
					return true;
				}
				if(!isset($args[1])) {
					$sender->sendMessage(self::CHAT_PREFIX.TextFormat::RED."Invalid Command Syntax! ".TextFormat::GRAY."Missing Parameter(s). ".TextFormat::YELLOW."/".$label." pattern <pattern_id : int>");
					return true;
				}
				// TODO:
			break;
			default:
				$sender->sendMessage(self::CHAT_PREFIX.TextFormat::RED."Invalid command syntax! ".TextFormat::GRAY."Unknown parameter ".TextFormat::AQUA.$args[0].TextFormat::GRAY.". ".TextFormat::YELLOW."/".$label." <reload|version|blocklist|toggle|pattern>");
				return true;
		}
		return true;
	}

	public function onBreak(BlockBreakEvent $event) {
		//
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	private function canVeinMine(Player $player) : bool {
		if($player->hasPermission("veinminer.veinmine.*")) return true;
		foreach($this->toolCategories as $category) {
			if($player->hasPermission("veinminer.veinmine.".$category)) return true;
		}
		return false;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	private function hasBlockListPerms(Player $player) : bool {
		return $player->hasPermission("veinminer.blocklist.add") or $player->hasPermission("veinminer.blocklist.remove") or $player->hasPermission("veinminer.blocklist.list.*");
	}

	public function getBlockList(?string $category) : array {
		//
	}

	public function isVeinMineable(Block $block, ?string $category) : bool {
		return in_array($block->getName(), $this->globalList) or in_array($block->getName(), $this->getBlockList($category));
	}

	public function loadVeinableBlocks() {
		//
	}

	public function loadToolTemplates() {
		//
	}

	public function loadDisabledWorlds() {
		//
	}

	public function loadMaterialAliases() {
		//
	}
}