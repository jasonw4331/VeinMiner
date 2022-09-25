<?php
declare(strict_types=1);
namespace jasonwynn10\VeinMiner\utils;

final class VMConstants{

	// Configuration options
	public CONST CONFIG_PERFORM_UPDATE_CHECKS = "PerformUpdateChecks";

	public CONST CONFIG_DEFAULT_ACTIVATION_STRATEGY = "DefaultActivationStrategy";
	public CONST CONFIG_VEIN_MINING_PATTERN = "VeinMiningPattern";
	public CONST CONFIG_SORT_BLOCKLIST_ALPHABETICALLY = "SortBlocklistAlphabetically";
	public CONST CONFIG_COLLECT_ITEMS_AT_SOURCE = "CollectItemsAtSource";

	public CONST CONFIG_REPAIR_FRIENDLY_VEINMINER = "RepairFriendlyVeinminer";
	public CONST CONFIG_INCLUDE_EDGES = "IncludeEdges";
	public CONST CONFIG_MAX_VEIN_SIZE = "MaxVeinSize";
	public CONST CONFIG_COST = "Cost";

	public CONST CONFIG_DISABLED_GAME_MODES = "DisabledGameModes";
	public CONST CONFIG_DISABLED_WORLDS = "DisabledWorlds";

	public CONST CONFIG_HUNGER_HUNGER_MODIFIER = "Hunger.HungerModifier";
	public CONST CONFIG_HUNGER_MINIMUM_FOOD_LEVEL = "Hunger.MinimumFoodLevel";
	public CONST CONFIG_HUNGER_HUNGRY_MESSAGE = "Hunger.HungryMessage";

	public CONST CONFIG_ALIASES = "Aliases";

	// Permission nodes
	public CONST PERMISSION_RELOAD = "veinminer.reload";
	public CONST PERMISSION_TOGGLE = "veinminer.toggle";
	public CONST PERMISSION_MODE = "veinminer.mode";
	public CONST PERMISSION_PATTERN = "veinminer.pattern";

	public CONST PERMISSION_FREE_ECONOMY = "veinminer.free.economy";
	public CONST PERMISSION_FREE_HUNGER = "veinminer.free.hunger";

	public CONST PERMISSION_BLOCKLIST_ADD = "veinminer.blocklist.add";
	public CONST PERMISSION_BLOCKLIST_REMOVE = "veinminer.blocklist.remove";
	public CONST PERMISSION_BLOCKLIST_LIST = "veinminer.blocklist.list";
	public CONST PERMISSION_TOOLLIST_ADD = "veinminer.toollist.add";
	public CONST PERMISSION_TOOLLIST_REMOVE = "veinminer.toollist.remove";
	public CONST PERMISSION_TOOLLIST_LIST = "veinminer.toollist.list";

	// Dynamic permission nodes
	public CONST PERMISSION_DYNAMIC_VEINMINE = "veinminer.veinmine.%s";

	private function __construct(){}
}