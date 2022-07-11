<?php
declare(strict_types=1);
namespace jasonwynn10\VeinMiner\api;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use jasonwynn10\VeinMiner\data\BlockList;
use jasonwynn10\VeinMiner\data\MaterialAlias;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use jasonwynn10\VeinMiner\tool\ToolTemplateItemStack;
use jasonwynn10\VeinMiner\utils\VMConstants;
use jasonwynn10\VeinMiner\VeinMiner;
use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use Ramsey\Collection\Set;

final class VeinMinerManager{

	private BlockList $globalBlockList;
	/** @var Set<MaterialAlias> $aliases */
	private Set $aliases;
	/** @var Set<GameMode> $disabledGameModes */
	private Set $disabledGameModes;

	private AlgorithmConfig $config;

	/** @var bool[] $playerVeinMining */
	private array $playerVeinMining;
	/** @var Position[] $blockToBeVeinMined */
	private array $blockToBeVeinMined;

	public function __construct(private VeinMiner $plugin){
		$this->globalBlockList = new BlockList();
		$this->aliases = new Set(MaterialAlias::class);
		$this->disabledGameModes = new Set(GameMode::class);
		$this->config = new AlgorithmConfig($plugin->getConfig()->getAll());
	}

	/**
	 * Get the global blocklist. This blocklist represents blocks and states listed by
	 * the "All" category in the configuration file.
	 *
	 * @return BlockList the global blocklist
	 */
	public function getBlockListGlobal() : BlockList{
		return $this->globalBlockList;
	}

	/**
	 * Get a {@link BlockList} of all veinmineable blocks. The returned blocklist will
	 * contain unique block-state combinations from all categories and the global blocklist.
	 * Any changes made to the returned block list will not affect the underlying blocklist,
	 * therefore if any changes are required, they should be done to those returned by
	 * {@link ToolCategory#getBlocklist()} or {@link #getBlockListGlobal()}
	 *
	 * @return BlockList get all veinmineable blocks
	 *
	 * @see ToolCategory#getBlocklist()
	 * @see #getBlockListGlobal()
	 */
	public function getAllVeinMineableBlocks() : BlockList {
		$categories = ToolCategory::getAll();
		$lists = new \SplFixedArray(count($categories) + 1);

		$index = 0;
		foreach ($categories as $category) {
			$lists[$index++] = $category->getBlocklist();
		}
		$lists[$index] = $this->globalBlockList;

		return new BlockList(...$lists->toArray());
	}

	/**
	 * Check whether the specified {@link Block} is vein mineable for the specified category.
	 *
	 * @param Block|BlockIdentifier $data the data to check
	 * @param ToolCategory|null $category the category to check
	 *
	 * @return true if the data is vein mineable by the specified category, false otherwise
	 */
	public function isVeinMineable(Block|BlockIdentifier $data, ?ToolCategory $category = null) : bool{
		if($category !== null){
			return $this->globalBlockList->contains($data) || $category->getBlocklist()->contains($data);
		}
		if ($this->globalBlockList->contains($data)) {
			return true;
		}

		foreach (ToolCategory::getAll() as $category) {
			if ($category->getBlocklist()->contains($data)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * See {@link BlockList#getVeinBlock(BlockData)}. This search includes the specified category
	 * as well as the global blocklist.
	 *
	 * @param Block|BlockIdentifier $data the block data for which to get a VeinBlock
	 * @param ToolCategory $category the category blocklist in which to retrieve a VeinBlock
	 *
	 * @return VeinBlock|null the vein block. null if none
	 */
	public function getVeinBlockFromBlockList(Block|BlockIdentifier $data, ToolCategory $category) : ?VeinBlock {
		$global = $this->globalBlockList->getVeinBlock($data);
		return ($global != null) ? $global : $category->getBlocklist()->getVeinBlock($data);
	}

	/**
	 * Get the global algorithm config.
	 *
	 * @return AlgorithmConfig global algorithm config
	 */
	public function getConfig() : AlgorithmConfig {
		return $this->config;
	}

	/**
	 * Load all veinable blocks from the configuration file to memory.
	 */
	public function loadVeinableBlocks() : void {
		$blocklistSection = $this->plugin->getConfig()->get("BlockList", null);
		if ($blocklistSection === null) {
			return;
		}

		foreach (array_keys($blocklistSection) as $tool) {
			$category = ToolCategory::get($tool);
			if ($category === null) {
				if (mb_strtolower($tool) !== 'all' && mb_strtolower($tool) !== 'hand') { // Special case for "all" and "hand". Don't show an error
					$this->plugin->getLogger()->warning("Attempted to create blocklist for the non-existent category, " . $tool . "... ignoring.");
				}

				continue;
			}

			$blocklist = $category->getBlocklist();
			$blocks = $this->plugin->getConfig()->getNested("BlockList." . $tool, []);

			foreach ($blocks as $value) {
				$block = StringToItemParser::getInstance()->parse($value);
				if (!$block instanceof ItemBlock) {
					$this->plugin->getLogger()->warning("Unknown block type (was it an item?) and/or block states for blocklist \"" . $category->getId() . "\". Given: " . $value);
					continue;
				}

				$blocklist->add($block->getBlock());
			}
		}
	}

	/**
	 * Load all tool categories from the configuration file to memory.
	 */
	public function loadToolCategories() : void {
		$categoriesConfig = $this->plugin->getCategoriesConfig();

		foreach($categoriesConfig->getAll() as $key => $categoryRoot) {
			if ($categoryRoot === null) {
				continue;
			}

			$category = new ToolCategory($key, new AlgorithmConfig($categoryRoot, $this->config));
			ToolCategory::register($category);

			$itemsList = $categoryRoot["Items"] ?? null;
			if ($itemsList === null) {
				$this->plugin->getLogger()->critical("No item list provided for category with ID " . $category->getId());
				continue;
			}

			foreach($itemsList as $tool) {
				$template = null;
				if ($tool === null) {
					continue;
				}

				if (is_string($tool)) {
					$type = StringToItemParser::getInstance()->parse($tool);
					if ($type === null) {
						$this->plugin->getLogger()->warning("Unknown tool of type \"" . $tool . "\" provided, ignoring...");
						continue;
					}

					$template = new ToolTemplateItemStack($category, $type);
				}elseif(is_array($tool)){
					// Material value
					$item = $this->getMaterialKey($tool);
					if ($item === null) {
						$this->plugin->getLogger()->warning("Tried to create item with missing or invalid type... material must be declared");
						continue;
					}

					// Additional meta
					$name = $tool['Name'];
					$lore = $tool['Lore'];

					if (is_string($name)) {
						$item->setCustomName(TextFormat::colorize($name));
					}

					if (is_string($lore)) {
						$item->setLore([TextFormat::colorize($lore, '&')]);
					} elseif (is_array($lore)) {
						$loreList = array_map(static fn(string $s) => TextFormat::colorize($s), array_filter($lore, static fn($value) => is_string($value)));
						if (count($loreList) < 1) {
							continue;
						}

						$item->setLore($loreList);
					}

					$templateAlgorithmConfig = clone $category->getConfig();
					$templateAlgorithmConfig->readUnsafe($tool); // If I can get rid of this, do it
					$template = new ToolTemplateItemStack($category, $item);
				}

				if ($template !== null) {
					$category->addTool($template);
				}
			}
		}

		// Handle dynamic permissions
		$categories = ToolCategory::getAll();
		if (count($categories) >= 1) {
			$veinminePermissionParent = $this->getOrRegisterPermission("veinminer.veinmine.*");
			$blocklistPermissionParent = $this->getOrRegisterPermission("veinminer.blocklist.list.*");
			$toollistPermissionParent = $this->getOrRegisterPermission("veinminer.toollist.list.*");

			foreach($categories as $category) {
				$id = mb_strtolower($category->getId());

				$permissionManager = PermissionManager::getInstance();
				$root = $permissionManager->getPermission(DefaultPermissions::ROOT_OPERATOR);
				$user = $permissionManager->getPermission(DefaultPermissions::ROOT_USER);

				$veinminePermission = new Permission("veinminer.veinmine." . $id, "Allows players to vein mine using the " . $category->getId() . " category");
				$blocklistPermission = new Permission("veinminer.blocklist.list." . $id, "Allows players to list blocks in the " . $category->getId() . " category");
				$toollistPermission = new Permission("veinminer.toollist.list." . $id, "Allows players to list tools in the " . $category->getId() . " category");

				$veinminePermissionParent->addChild($veinminePermission->getName(), true);
				$blocklistPermissionParent->addChild($blocklistPermission->getName(), true);
				$toollistPermissionParent->addChild($toollistPermission->getName(), true);

				DefaultPermissions::registerPermission($veinminePermission, [$root], []);
				DefaultPermissions::registerPermission($blocklistPermission, [$user], []);
				DefaultPermissions::registerPermission($toollistPermission, [$user], []);
			}

			$veinminePermissionParent->recalculatePermissibles();
			$blocklistPermissionParent->recalculatePermissibles();
			$toollistPermissionParent->recalculatePermissibles();
		}
	}

	/**
	 * Load all disabled game modes from the configuration file to memory.
	 */
	public function loadDisabledGameModes() : void {
		$this->disabledGameModes->clear();

		foreach($this->plugin->getConfig()->get(VMConstants::CONFIG_DISABLED_GAME_MODES) as $gamemodeString){
			$gamemode = GameMode::fromString($gamemodeString);
			if ($gamemode === null) {
				return;
			}
			$this->disabledGameModes->add($gamemode);
		}
	}

	/**
	 * Add a game mode to the disabled list.
	 *
	 * @param GameMode $gamemode the game mode to add
	 */
	public function addDisabledGameMode(GameMode $gamemode) : void {
		$this->disabledGameModes->add($gamemode);
	}

	/**
	 * Remove a game mode from the disabled list.
	 *
	 * @param GameMode $gamemode the game mode to remove
	 */
	public function removeDisabledGameMode(GameMode $gamemode) : void {
		$this->disabledGameModes->remove($gamemode);
	}

	/**
	 * Check whether or not the specific game mode is disabled.
	 *
	 * @param GameMode $gamemode the game mode to check
	 *
	 * @return true if disabled, false otherwise
	 */
	public function isDisabledGameMode(GameMode $gamemode) : bool {
		return $this->disabledGameModes->contains($gamemode); // TODO: use EnumTrait::equals()
	}

	/**
	 * Register a new MaterialAlias.
	 *
	 * @param MaterialAlias $alias the alias to register
	 */
	public function registerAlias(MaterialAlias $alias) : void {
		$this->aliases->add($alias);
	}

	/**
	 * Unregister a MaterialAlias.
	 *
	 * @param MaterialAlias $alias the alias to unregister
	 */
	public function unregisterAlias(MaterialAlias $alias) : void {
		$this->aliases->remove($alias);
	}

	/**
	 * Get the alias associated with a specific block data.
	 *
	 * @param Block|BlockIdentifier $data the block data to reference
	 *
	 * @return MaterialAlias|null the associated alias. null if none
	 */
	public function getAliasFor(Block|BlockIdentifier $data) : ?MaterialAlias {
		if($data instanceof Block){
			return $this->getAliasFor($data->getIdInfo());
		}
		foreach ($this->aliases as $alias) {
			if ($alias->isAliased($data)) {
				return $alias;
			}
		}
		return null;
	}

	/**
	 * Load all material aliases from config to memory.
	 */
	public function loadMaterialAliases() : void {
		$this->aliases->clear();

		foreach ($this->plugin->getConfig()->get(VMConstants::CONFIG_ALIASES) as $aliasList) {
			$alias = new MaterialAlias();
			foreach(explode(',', $aliasList) as $aliasState) {
				$block = VeinBlock::fromString($aliasState);
				if($block === null){
					$this->plugin->getLogger()->warning("Unknown block type (was it an item?) and/or block states for alias \"" . $aliasList . "\". Given: " . $aliasState);
					continue;
				}

				$alias->addAlias($block);
			}

			$this->aliases[] = $alias;
		}
	}

	/**
	 * Clear all localised data in the VeinMiner Manager.
	 */
	public function clearLocalisedData() : void {
		$this->globalBlockList->clear();
		$this->aliases->clear();
		$this->disabledGameModes->clear();
	}

	private function getMaterialKey(array $map) : ?Item {
		$possibleMapping = $map['Material'];
		if (is_string($possibleMapping)) {
			return StringToItemParser::getInstance()->parse($possibleMapping);
		}
		foreach($map as $key => $entry) {
			if ($entry === null) {
				return StringToItemParser::getInstance()->parse($key);
			}
		}

		return VanillaItems::AIR();
	}

	private function getOrRegisterPermission(string $permissionName) : Permission {
		$permissionManager = PermissionManager::getInstance();
		$permission = $permissionManager->getPermission($permissionName);
		if ($permission === null) {
			$permission = new Permission($permissionName);
			$root = $permissionManager->getPermission(DefaultPermissions::ROOT_OPERATOR);
			$permission = DefaultPermissions::registerPermission($permission, [$root], []);
		}
		return $permission;
	}

	public function setPlayerVeinMining(Player $player) : void {
		$this->playerVeinMining[$player->getName()] = true;
	}

	public function isPlayerVeinMining(Player $player) : bool {
		return isset($this->playerVeinMining[$player->getName()]);
	}

	public function removePlayerVeinMining(Player $player) : void {
		if(isset($this->playerVeinMining[$player->getName()]))
			unset($this->playerVeinMining[$player->getName()]);
	}

	public function setBlockToBeVeinMined(Block $block, Block $origin) : void {
		$pos = $block->getPosition();
		$this->blockToBeVeinMined[$pos->x.':'.$pos->y.':'.$pos->z.':'.$pos->world->getFolderName()] = $origin->getPosition();
	}

	public function getBlockToBeVeinMinedOrigin(Block $block) : ?Position {
		$pos = $block->getPosition();
		$key = $pos->x.':'.$pos->y.':'.$pos->z.':'.$pos->world->getFolderName();
		if(isset($this->blockToBeVeinMined[$key])){
			return $this->blockToBeVeinMined[$key];
		}
		return null;
	}

	public function removeBlockToBeVeinMined($block) : void {
		$pos = $block->getPosition();
		$key = $pos->x.':'.$pos->y.':'.$pos->z.':'.$pos->world->getFolderName();
		if(isset($this->blockToBeVeinMined[$key])){
			unset($this->blockToBeVeinMined[$key]);
		}
	}
}