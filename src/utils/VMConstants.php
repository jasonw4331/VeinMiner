<?php
declare(strict_types=1);

namespace jasonw4331\VeinMiner\utils;

final class VMConstants{

	// Configuration options
	public const CONFIG_PERFORM_UPDATE_CHECKS = "PerformUpdateChecks";

	public const CONFIG_DEFAULT_ACTIVATION_STRATEGY = "DefaultActivationStrategy";
	public const CONFIG_VEIN_MINING_PATTERN = "VeinMiningPattern";
	public const CONFIG_SORT_BLOCKLIST_ALPHABETICALLY = "SortBlocklistAlphabetically";
	public const CONFIG_COLLECT_ITEMS_AT_SOURCE = "CollectItemsAtSource";

	public const CONFIG_REPAIR_FRIENDLY_VEINMINER = "RepairFriendlyVeinminer";
	public const CONFIG_INCLUDE_EDGES = "IncludeEdges";
	public const CONFIG_MAX_VEIN_SIZE = "MaxVeinSize";
	public const CONFIG_COST = "Cost";

	public const CONFIG_DISABLED_GAME_MODES = "DisabledGameModes";
	public const CONFIG_DISABLED_WORLDS = "DisabledWorlds";

	public const CONFIG_HUNGER_HUNGER_MODIFIER = "Hunger.HungerModifier";
	public const CONFIG_HUNGER_MINIMUM_FOOD_LEVEL = "Hunger.MinimumFoodLevel";
	public const CONFIG_HUNGER_HUNGRY_MESSAGE = "Hunger.HungryMessage";

	public const CONFIG_ALIASES = "Aliases";

	// Permission nodes
	public const PERMISSION_RELOAD = "veinminer.reload";
	public const PERMISSION_TOGGLE = "veinminer.toggle";
	public const PERMISSION_MODE = "veinminer.mode";
	public const PERMISSION_PATTERN = "veinminer.pattern";

	public const PERMISSION_FREE_ECONOMY = "veinminer.free.economy";
	public const PERMISSION_FREE_HUNGER = "veinminer.free.hunger";

	public const PERMISSION_BLOCKLIST_ADD = "veinminer.blocklist.add";
	public const PERMISSION_BLOCKLIST_REMOVE = "veinminer.blocklist.remove";
	public const PERMISSION_BLOCKLIST_LIST = "veinminer.blocklist.list";
	public const PERMISSION_TOOLLIST_ADD = "veinminer.toollist.add";
	public const PERMISSION_TOOLLIST_REMOVE = "veinminer.toollist.remove";
	public const PERMISSION_TOOLLIST_LIST = "veinminer.toollist.list";

	// Dynamic permission nodes
	public const PERMISSION_DYNAMIC_VEINMINE = "veinminer.veinmine.%s";

	private function __construct(){ }
}
