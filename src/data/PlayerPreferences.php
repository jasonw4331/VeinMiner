<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\data;

use jasonwynn10\VeinMiner\api\ActivationStrategy;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use jasonwynn10\VeinMiner\VeinMiner;
use pocketmine\player\IPlayer;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Filesystem;
use Ramsey\Collection\Set;
use Webmozart\PathUtil\Path;

final class PlayerPreferences{

	private static array $CACHE = [];

	private ActivationStrategy $activationStrategy;
	/** @var Set<ToolCategory> $disabledCategories */
	private Set $disabledCategories;

	private bool $dirty = false;

	public function __construct(private string $player){
		$this->activationStrategy = ActivationStrategy::getDefaultActivationStrategy();
		$this->disabledCategories = new Set(ToolCategory::class);
	}

	/**
	 * Get the player to which this data belongs.
	 *
	 * @return IPlayer the owning player
	 */
	public function getPlayer() : IPlayer{
		return Server::getInstance()->getOfflinePlayer($this->player);
	}

	/**
	 * Enable VeinMiner for this player
	 */
	public function enableVeinMiner(?ToolCategory $category = null) : void{
		if($category !== null) {
			$this->disabledCategories->remove($category);
			return;
		}
		$this->dirty = $this->disabledCategories->count() > 0;
		$this->disabledCategories->clear();
	}

	/**
	 * Disable VeinMiner for this player
	 */
	public function disableVeinMiner(?ToolCategory $category = null) : void{
		$this->dirty = true;
		if($category !== null) {
			$this->disabledCategories->add($category);
			return;
		}
		foreach(ToolCategory::getAll() as $category) {
			$this->disabledCategories->add($category);
		}
	}

	/**
	 * Set VeinMiner's enabled state for this player
	 *
	 * @param bool $enable whether or not to enable VeinMiner
	 */
	public function setVeinMinerEnabled(bool $enable, ?ToolCategory $category = null) : void{
		$enable ? $this->enableVeinMiner($category) : $this->disableVeinMiner($category);
	}

	/**
	 * Check whether or not VeinMiner is enabled for this player
	 *
	 * @return true if enabled, false otherwise
	 */
	public function isVeinMinerEnabled(?ToolCategory $category = null) : bool{
		if($category !== null) {
			return !$this->disabledCategories->contains($category);
		}
		return \count($this->disabledCategories) === 0;
	}

	/**
	 * Check whether or not VeinMiner is disabled for this player (all categories)
	 *
	 * @return true if disabled, false otherwise
	 */
	public function isVeinMinerDisabled(?ToolCategory $category = null) : bool{
		if($category !== null) {
			return $this->disabledCategories->contains($category);
		}
		return $this->disabledCategories->count() >= ToolCategory::getRegisteredAmount();
	}

	/**
	 * Check whether or not VeinMiner is disabled in at least one category. This is effectively
	 * the inverse of {@link #isVeinMinerEnabled()}.
	 *
	 * @return true if at least one category is disabled, false otherwise (all enabled)
	 */
	public function isVeinMinerPartiallyDisabled() : bool{
		return $this->disabledCategories->count() > 0;
	}

	/**
	 * Set the activation strategy to use for this player.
	 *
	 * @param ActivationStrategy $activationStrategy the activation strategy
	 */
	public function setActivationStrategy(ActivationStrategy $activationStrategy) : void{
		$this->dirty = $this->activationStrategy !== $activationStrategy;
		$this->activationStrategy = $activationStrategy;
	}

	/**
	 * Get the activation strategy to use for this player.
	 *
	 * @return ActivationStrategy the activation strategy
	 */
	public function getActivationStrategy() : ActivationStrategy{
		return $this->activationStrategy;
	}

	/**
	 * Set whether or not this player data should be written.
	 *
	 * @param bool $dirty true if dirty, false otherwise
	 */
	public function setDirty(bool $dirty) : void{
		$this->dirty = $dirty;
	}

	/**
	 * Check whether or not this player data has been modified since last write.
	 *
	 * @return true if modified, false otherwise
	 */
	public function isDirty() : bool{
		return $this->dirty;
	}

	/**
	 * Write this object's data into the provided JsonObject.
	 *
	 * @param array $root the array in which to write the data
	 *
	 * @return array the modified instance of the provided object
	 */
	public function write(array $root) : array{
		$root['activation_strategy'] = $this->activationStrategy->name();

		/** @var ToolCategory[] $disabledCategories */
		$disabledCategories = $this->disabledCategories->toArray();
		foreach($disabledCategories as $category) {
			$root['disabled_categories'][] = $category->getId();
		}

		return $root;
	}

	/**
	 * Read data from the provided array into this object.
	 *
	 * @param array $root the object from which to read data
	 */
	public function read(array $root) : void{
		if(isset($root['activation_strategy'])) {
			try{
				/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
				$this->activationStrategy = ActivationStrategy::__callStatic($root['activation_strategy'], []);
			}catch(\Error $e) {
				$this->activationStrategy = ActivationStrategy::getDefaultActivationStrategy();
			}
		}

		if(isset($root['disabled_categories'])) {
			foreach($root['disabled_categories'] as $category) {
				$this->disabledCategories->add(ToolCategory::get($category));
			}
		}

		$this->dirty = false;
	}

	/**
	 * Write this object to its file in the specified directory.
	 *
	 * @param string $directory the directory in which the file resides
	 *
	 * @see VeinMiner#getPlayerDataDirectory()
	 */
	public function writeToFile(string $directory) : void{
		if(!\is_dir($directory)) {
			throw new \InvalidArgumentException("Provided directory is not a directory");
		}
		try{
			FileSystem::safeFilePutContents(
				Path::join($directory, $this->player . '.json'),
				\json_encode($this->write([]), flags: JSON_THROW_ON_ERROR)
			);
		}catch(\JsonException|\RuntimeException $e){
			VeinMiner::getInstance()->getLogger()->logException($e);
		}
	}

	/**
	 * Read this object from its file in the specified directory.
	 *
	 * @param string $directory the directory in which the file resides
	 *
	 * @see VeinMiner#getPlayerDataDirectory()
	 */
	public function readFromFile(string $directory) : void{
		if(!\is_dir($directory)) {
			throw new \InvalidArgumentException("Provided directory is not a directory");
		}
		$file = Path::join($directory, $this->player . '.json');
		if(!\file_exists($file)) {
			return;
		}

		try{
			$this->read(\json_decode(\file_get_contents($file), true, flags: \JSON_THROW_ON_ERROR));
		}catch(\JsonException $e) {
			VeinMiner::getInstance()->getLogger()->warning('Could not read player data for user ' . $this->player . '. Invalid file format. Deleting...');
			\unlink($file);
		}
	}

	/**
	 * Get the {@link PlayerPreferences} instance for the specified player.
	 *
	 * @param Player $player the player whose data to retrieve
	 *
	 * @return PlayerPreferences the player data
	 */
	public static function get(Player $player) : PlayerPreferences{
		return self::$CACHE[$player->getName()] ?? (self::$CACHE[$player->getName()] = new PlayerPreferences($player->getName()));
	}

	/**
	 * Clear the player data cache (all player-specific data will be lost)
	 */
	public static function clearCache() : void{
		self::$CACHE = [];
	}

}