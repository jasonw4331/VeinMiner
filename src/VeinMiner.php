<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner;

use jasonwynn10\VeinMiner\api\VeinMinerManager;
use jasonwynn10\VeinMiner\commands\VeinMinerCommand;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use jasonwynn10\VeinMiner\data\PlayerPreferences;
use jasonwynn10\VeinMiner\economy\CapitalBasedEconomyModifier;
use jasonwynn10\VeinMiner\economy\EconomyModifier;
use jasonwynn10\VeinMiner\economy\EmptyEconomyModifier;
use jasonwynn10\VeinMiner\listener\BreakBlockListener;
use jasonwynn10\VeinMiner\listener\PlayerDataListener;
use jasonwynn10\VeinMiner\pattern\PatternExpansive;
use jasonwynn10\VeinMiner\pattern\PatternRegistry;
use jasonwynn10\VeinMiner\pattern\PatternThorough;
use jasonwynn10\VeinMiner\pattern\VeinMiningPattern;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use jasonwynn10\VeinMiner\utils\NamespacedKey;
use jasonwynn10\VeinMiner\utils\VMConstants;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Webmozart\PathUtil\Path;
use function mkdir;

final class VeinMiner extends PluginBase implements Listener{
	use SingletonTrait {
		reset as private _reset; // don't let someone delete our instance
		setInstance as private _setInstance; // don't let someone set our instance
	}

	public static string $BLOCK_DATA_PATTERN = '/(?:[\w:]+)(?:\[(.+=.+)+\])*/i';

	private VeinMinerManager $manager;
	private PatternRegistry $patternRegistry;
	private EconomyModifier $economyModifier;

	private ?VeinMiningPattern $veinMiningPattern = null;

	private Config $categoriesConfig;
	private string $playerDataDirectory;

	protected function onLoad() : void{
		self::_setInstance($this);
	}

	protected function onEnable() : void{
		$this->manager = new VeinMinerManager($this);

		// Configuration handling
		$this->saveResource('categories.yml');
		$this->categoriesConfig = new Config(Path::join($this->getDataFolder(), 'categories.yml'));
		$this->playerDataDirectory = Path::join($this->getDataFolder(), 'playerdata');
		@mkdir($this->playerDataDirectory);

		// Pattern registration
		$this->patternRegistry = new PatternRegistry();
		$this->patternRegistry->registerPattern(PatternThorough::getInstance());
		$this->patternRegistry->registerPattern(PatternExpansive::getInstance());

		// TODO: Enable anticheat hooks if required

		// register events
		$this->getLogger()->debug('Registering events');
		$manager = $this->getServer()->getPluginManager();
		$manager->registerEvents(new BreakBlockListener($this), $this);
		$manager->registerEvents(new PlayerDataListener($this), $this);

		// Register commands
		$this->getLogger()->debug('Registering commands');
		/** @noinspection PhpPossiblePolymorphicInvocationInspection */
		$this->getCommand('veinminer')->setExecutor(new VeinMinerCommand($this));

		if($this->getServer()->getPluginManager()->getPlugin('Capital') !== null){
			$this->getLogger()->debug('Capital found. Attempting to enable economy support...');
			$this->economyModifier = new CapitalBasedEconomyModifier();
			$this->getLogger()->debug($this->economyModifier->hasEconomyPlugin() ? 'Economy found! Hooked successfully.' : 'Cancelled. No economy plugin found.');
		}else{
			$this->getLogger()->debug('No economy plugin found. Economy support suspended');
			$this->economyModifier = EmptyEconomyModifier::getInstance();
		}

		// load blocks to the veinable list
		$this->getLogger()->debug('Loading configuration options to local memory');
		$this->manager->loadToolCategories();
		$this->manager->loadVeinableBlocks();
		$this->manager->loadMaterialAliases();

		// Special case for reloads and crashes (no longer needed in API 4)
		foreach($this->getServer()->getOnlinePlayers() as $player){
			PlayerPreferences::get($player)->readFromFile($this->playerDataDirectory);
		}

		// TODO: automatic update check
	}

	protected function onDisable() : void{
		$this->getLogger()->debug('Clearing localized data');
		$this->manager->clearLocalisedData();
		$this->patternRegistry->clearPatterns();

		// Special case for reloads and crashes (no longer needed in API 4)
		foreach($this->getServer()->getOnlinePlayers() as $player){
			$playerData = PlayerPreferences::get($player);
			if(!$playerData->isDirty())
				return;

			$playerData->writeToFile($this->playerDataDirectory);
		}

		PlayerPreferences::clearCache();
		VeinBlock::clearCache();
		ToolCategory::clearCategories();
	}

	/**
	 * Get the VeinMiner Manager used to keep track of Veinminable blocks, and other utilities.
	 *
	 * @return VeinMinerManager an instance of the VeinMiner manager
	 */
	public function getVeinMinerManager() : VeinMinerManager{
		return $this->manager;
	}

	/**
	 * Get the pattern registry used to register custom vein mining patterns.
	 *
	 * @return PatternRegistry an instance of the pattern registry
	 */
	public function getPatternRegistry() : PatternRegistry{
		return $this->patternRegistry;
	}

	/**
	 * Get an instance of the categories configuration file.
	 *
	 * @return Config the categories config
	 */
	public function getCategoriesConfig() : Config{
		return $this->categoriesConfig;
	}

	/**
	 * Get VeinMiner's playerdata directory.
	 *
	 * @return string the playerdata directory
	 */
	public function getPlayerDataDirectory() : string{
		return $this->playerDataDirectory;
	}

	/**
	 * Get the economy abstraction layer for a Vault economy.
	 *
	 * @return EconomyModifier economy abstraction
	 */
	public function getEconomyModifier() : EconomyModifier{
		return $this->economyModifier;
	}

	/**
	 * Set the vein mining pattern to use.
	 *
	 * @param VeinMiningPattern $pattern the pattern to set
	 */
	public function setVeinMiningPattern(VeinMiningPattern $pattern) : void{
		$this->veinMiningPattern = $pattern;
	}

	/**
	 * Get the vein mining pattern to use.
	 *
	 * @return VeinMiningPattern the pattern
	 */
	public function getVeinMiningPattern() : VeinMiningPattern{
		if($this->veinMiningPattern === null){
			$patternKeyString = $this->getConfig()->get(VMConstants::CONFIG_VEIN_MINING_PATTERN, null);
			$patternKey = $patternKeyString ?? new NamespacedKey($this, $patternKeyString);

			if($patternKey == null){
				$this->getLogger()->warning("Malformatted pattern key, " . $patternKeyString . ". Expected \"foo:bar\" format.");
				$patternKey = PatternExpansive::getInstance()->getKey();
			}

			$pattern = $this->patternRegistry->getPattern($patternKey);
			if($pattern === null){
				$this->getLogger()->warning("Unrecognized pattern. Could not find pattern with id " . $patternKey . ". Was it spelt correctly?");
				$pattern = PatternExpansive::getInstance()->getKey();
			}

			$this->veinMiningPattern = $pattern;
		}

		return $this->veinMiningPattern;
	}

}
