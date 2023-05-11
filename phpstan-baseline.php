<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between \'Malformatted…\' and 0\\|0\\.0\\|\'\'\\|\'0\'\\|array\\{\\}\\|false\\|null results in an error\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/VeinMiner.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot call method setExecutor\\(\\) on \\(pocketmine\\\\command\\\\Command&pocketmine\\\\plugin\\\\PluginOwned\\)\\|null\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/VeinMiner.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\VeinMiner\\:\\:getVeinMiningPattern\\(\\) should return jasonw4331\\\\VeinMiner\\\\pattern\\\\VeinMiningPattern but returns jasonw4331\\\\VeinMiner\\\\pattern\\\\VeinMiningPattern\\|jasonw4331\\\\VeinMiner\\\\utils\\\\NamespacedKey\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/VeinMiner.php',
];
$ignoreErrors[] = [
	'message' => '#^Negated boolean expression is always false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/VeinMiner.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$key of method jasonw4331\\\\VeinMiner\\\\pattern\\\\PatternRegistry\\:\\:getPattern\\(\\) expects string, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/VeinMiner.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$key of class jasonw4331\\\\VeinMiner\\\\utils\\\\NamespacedKey constructor expects string, array\\|float\\|int\\|string\\|false\\|null given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/VeinMiner.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\VeinMiner\\:\\:\\$veinMiningPattern \\(jasonw4331\\\\VeinMiner\\\\pattern\\\\VeinMiningPattern\\|null\\) does not accept jasonw4331\\\\VeinMiner\\\\pattern\\\\VeinMiningPattern\\|jasonw4331\\\\VeinMiner\\\\utils\\\\NamespacedKey\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/VeinMiner.php',
];
$ignoreErrors[] = [
	'message' => '#^Ternary operator condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/VeinMiner.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\ActivationStrategy\\:\\:SNEAK\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/api/ActivationStrategy.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$name of static method jasonw4331\\\\VeinMiner\\\\api\\\\ActivationStrategy\\:\\:__callStatic\\(\\) expects string, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/ActivationStrategy.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$x of method pocketmine\\\\world\\\\World\\:\\:getBlockAt\\(\\) expects int, float\\|int given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VBlockFace.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$y of method pocketmine\\\\world\\\\World\\:\\:getBlockAt\\(\\) expects int, float\\|int given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VBlockFace.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$z of method pocketmine\\\\world\\\\World\\:\\:getBlockAt\\(\\) expects int, float\\|int given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VBlockFace.php',
];
$ignoreErrors[] = [
	'message' => '#^Argument of an invalid type mixed supplied for foreach, only iterables are supported\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:getBlockList\\(\\) with incorrect case\\: getBlocklist$#',
	'count' => 3,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access offset \'Items\' on mixed\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot call method getFolderName\\(\\) on pocketmine\\\\world\\\\World\\|null\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Left side of \\|\\| is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\api\\\\VeinMinerManager\\:\\:getAllVeinMineableBlocks\\(\\) return type has no value type specified in iterable type jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\api\\\\VeinMinerManager\\:\\:getBlockListGlobal\\(\\) return type has no value type specified in iterable type jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\api\\\\VeinMinerManager\\:\\:getMaterialKey\\(\\) has parameter \\$map with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\api\\\\VeinMinerManager\\:\\:isDisabledGameMode\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\api\\\\VeinMinerManager\\:\\:removeBlockToBeVeinMined\\(\\) has parameter \\$block with no type specified\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$array of function array_keys expects array, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$data of method jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\:\\:contains\\(\\) expects jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlock\\|pocketmine\\\\block\\\\BlockIdentifier, pocketmine\\\\block\\\\Block\\|pocketmine\\\\block\\\\BlockIdentifier given\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$data of method jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\:\\:getVeinBlock\\(\\) expects pocketmine\\\\block\\\\BlockIdentifier, pocketmine\\\\block\\\\Block\\|pocketmine\\\\block\\\\BlockIdentifier given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$id of class jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory constructor expects string, int\\|string given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$id of static method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:get\\(\\) expects pocketmine\\\\item\\\\Item\\|string, int\\|string given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$input of method pocketmine\\\\item\\\\StringToItemParser\\:\\:parse\\(\\) expects string, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$provided of class jasonw4331\\\\VeinMiner\\\\data\\\\AlgorithmConfig constructor expects array, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$str of static method pocketmine\\\\player\\\\GameMode\\:\\:fromString\\(\\) expects string, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$string of function mb_strtolower expects string, int\\|string given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$grantedBy of static method pocketmine\\\\permission\\\\DefaultPermissions\\:\\:registerPermission\\(\\) expects array\\<pocketmine\\\\permission\\\\Permission\\>, array\\<int, pocketmine\\\\permission\\\\Permission\\|null\\> given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$string of function explode expects string, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\api\\\\VeinMinerManager\\:\\:\\$globalBlockList type has no value type specified in iterable type jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Right side of \\|\\| is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Unreachable statement \\- code above always terminates\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/VeinMinerManager.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\api\\\\event\\\\PlayerVeinMineEvent\\:\\:__construct\\(\\) has parameter \\$blocks with generic class Ramsey\\\\Collection\\\\Set but does not specify its types\\: T$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/event/PlayerVeinMineEvent.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\api\\\\event\\\\PlayerVeinMineEvent\\:\\:getBlocks\\(\\) return type with generic class Ramsey\\\\Collection\\\\Set does not specify its types\\: T$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/event/PlayerVeinMineEvent.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\api\\\\event\\\\PlayerVeinMineEvent\\:\\:\\$player is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/api/event/PlayerVeinMineEvent.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between \'§f \\- §e\' and jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplate results in an error\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method pocketmine\\\\plugin\\\\Plugin\\:\\:getCategoriesConfig\\(\\)\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method pocketmine\\\\plugin\\\\Plugin\\:\\:getConfig\\(\\)\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method pocketmine\\\\plugin\\\\Plugin\\:\\:getPatternRegistry\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method pocketmine\\\\plugin\\\\Plugin\\:\\:getVeinMinerManager\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method pocketmine\\\\plugin\\\\Plugin\\:\\:saveConfig\\(\\)\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method pocketmine\\\\plugin\\\\Plugin\\:\\:setVeinMiningPattern\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:getBlockList\\(\\) with incorrect case\\: getBlocklist$#',
	'count' => 3,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method pocketmine\\\\command\\\\CommandSender\\:\\:sendMessage\\(\\) with incorrect case\\: sendmessage$#',
	'count' => 4,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method get\\(\\) on an unknown class jasonw4331\\\\VeinMiner\\\\commands\\\\UpdateChecker\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\commands\\\\VeinMinerCommand\\:\\:hasBlocklistPerms\\(\\) is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\commands\\\\VeinMinerCommand\\:\\:hasToolListPerms\\(\\) is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Negated boolean expression is always false\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$callback of function call_user_func expects callable\\(\\)\\: mixed, array\\{\'jasonw4331\\\\\\\\VeinMiner\\\\\\\\api\\\\\\\\ActivationStrategy\', string\\} given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$data of method jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\:\\:contains\\(\\) expects jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlock\\|pocketmine\\\\block\\\\BlockIdentifier, pocketmine\\\\block\\\\Block given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Ternary operator condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Unreachable statement \\- code above always terminates\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/commands/VeinMinerCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\AlgorithmConfig\\:\\:__construct\\(\\) has parameter \\$provided with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/AlgorithmConfig.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\AlgorithmConfig\\:\\:includesEdges\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/AlgorithmConfig.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\AlgorithmConfig\\:\\:isDisabledWorld\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/AlgorithmConfig.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\AlgorithmConfig\\:\\:isRepairFriendly\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/AlgorithmConfig.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\AlgorithmConfig\\:\\:readUnsafe\\(\\) has parameter \\$raw with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/AlgorithmConfig.php',
];
$ignoreErrors[] = [
	'message' => '#^PHPDoc tag @param has invalid value \\(world the world to check\\)\\: Unexpected token "the", expected variable at offset 97$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/AlgorithmConfig.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$cost of method jasonw4331\\\\VeinMiner\\\\data\\\\AlgorithmConfig\\:\\:cost\\(\\) expects float, float\\|int\\<48, 57\\>\\|int\\<256, max\\>\\|string given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/AlgorithmConfig.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\data\\\\AlgorithmConfig\\:\\:\\$disabledWorlds type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/AlgorithmConfig.php',
];
$ignoreErrors[] = [
	'message' => '#^Class jasonw4331\\\\VeinMiner\\\\data\\\\BlockList implements generic interface IteratorAggregate but does not specify its types\\: TKey, TValue$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\:\\:__construct\\(\\) has parameter \\$lists with no value type specified in iterable type jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\:\\:contains\\(\\) should return true but returns bool\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\:\\:containsExact\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\:\\:containsWildcard\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\:\\:getIterator\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlock\\:\\:isWildcard\\(\\) invoked with 1 parameter, 0 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\.\\.\\.\\$collections of method Ramsey\\\\Collection\\\\CollectionInterface\\<\\*NEVER\\*\\>\\:\\:merge\\(\\) expects Ramsey\\\\Collection\\\\CollectionInterface\\<\\*NEVER\\*\\>, Ramsey\\\\Collection\\\\Set\\<jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlock\\> given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\:\\:\\$blocks \\(Ramsey\\\\Collection\\\\Set\\<jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlock\\>\\) does not accept Ramsey\\\\Collection\\\\CollectionInterface\\<\\*NEVER\\*\\>\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/BlockList.php',
];
$ignoreErrors[] = [
	'message' => '#^Class jasonw4331\\\\VeinMiner\\\\data\\\\MaterialAlias implements generic interface IteratorAggregate but does not specify its types\\: TKey, TValue$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/MaterialAlias.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\MaterialAlias\\:\\:__clone\\(\\) with return type void returns jasonw4331\\\\VeinMiner\\\\data\\\\MaterialAlias but should not return anything\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/MaterialAlias.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\MaterialAlias\\:\\:addAlias\\(\\) has no return type specified\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/MaterialAlias.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\MaterialAlias\\:\\:isAliased\\(\\) should return true but returns bool\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/src/data/MaterialAlias.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\MaterialAlias\\:\\:removeAlias\\(\\) has no return type specified\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/MaterialAlias.php',
];
$ignoreErrors[] = [
	'message' => '#^Right side of && is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/MaterialAlias.php',
];
$ignoreErrors[] = [
	'message' => '#^Foreach overwrites \\$category with its value variable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:isDirty\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:isVeinMinerDisabled\\(\\) should return true but returns bool\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:isVeinMinerEnabled\\(\\) should return true but returns bool\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:isVeinMinerPartiallyDisabled\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:read\\(\\) has parameter \\$root with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:write\\(\\) has parameter \\$root with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:write\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$element of method Ramsey\\\\Collection\\\\AbstractSet\\<jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\>\\:\\:add\\(\\) expects jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory, jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\|null given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$json of function json_decode expects string, string\\|false given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$root of method jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:read\\(\\) expects array, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:\\$CACHE type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\data\\\\PlayerPreferences\\:\\:\\$activationStrategy \\(jasonw4331\\\\VeinMiner\\\\api\\\\ActivationStrategy\\) does not accept object\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/PlayerPreferences.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\BlockCache\\:\\:getOrCache\\(\\) should return jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlock but returns mixed\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/block/BlockCache.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\BlockCache\\:\\:MATERIAL\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/block/VeinBlock.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlock\\:\\:isWildcard\\(\\) should return true but returns false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/block/VeinBlock.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$data of class jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlockDatable constructor expects pocketmine\\\\block\\\\Block, pocketmine\\\\block\\\\Block\\|pocketmine\\\\block\\\\BlockIdentifier given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/block/VeinBlock.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlockDatable\\:\\:encapsulates\\(\\) should return true but returns bool\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/data/block/VeinBlockDatable.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$start of function mb_substr expects int, int\\<0, max\\>\\|false given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/block/VeinBlockDatable.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\data\\\\block\\\\VeinBlockWildcard\\:\\:hasSpecificData\\(\\) should return true but returns false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/block/VeinBlockWildcard.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method completeConfig\\(\\) on an unknown class SOFe\\\\Capital\\\\Capital\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method getMessage\\(\\) on an unknown class SOFe\\\\Capital\\\\CapitalException\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method takeMoney\\(\\) on an unknown class SOFe\\\\Capital\\\\Capital\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method api\\(\\) on an unknown class SOFe\\\\Capital\\\\Capital\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method resolve\\(\\) on an unknown class SOFe\\\\InfoAPI\\\\InfoAPI\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Caught class SOFe\\\\Capital\\\\CapitalException not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Class class@anonymous/economy/CapitalBasedEconomyModifier\\.php\\:51 does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class SOFe\\\\Capital\\\\LabelSet not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class SOFe\\\\InfoAPI\\\\PlayerInfo not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\economy\\\\CapitalBasedEconomyModifier\\:\\:hasEconomyPlugin\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\economy\\\\CapitalBasedEconomyModifier\\:\\:hasSufficientBalance\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\economy\\\\CapitalBasedEconomyModifier\\:\\:shouldCharge\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$api of anonymous function has invalid type SOFe\\\\Capital\\\\Capital\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\economy\\\\CapitalBasedEconomyModifier\\:\\:\\$selector has unknown class SOFe\\\\Capital\\\\Schema\\\\Complete as its type\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/CapitalBasedEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\economy\\\\EmptyEconomyModifier\\:\\:shouldCharge\\(\\) should return true but returns false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/economy/EmptyEconomyModifier.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method pocketmine\\\\player\\\\Player\\:\\:getGamemode\\(\\) with incorrect case\\: getGameMode$#',
	'count' => 1,
	'path' => __DIR__ . '/src/listener/BreakBlockListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\listener\\\\BreakBlockListener\\:\\:applyHungerDebuff\\(\\) is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/listener/BreakBlockListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\listener\\\\BreakBlockListener\\:\\:breakBlock\\(\\) is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/listener/BreakBlockListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Negated boolean expression is always false\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/src/listener/BreakBlockListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Result of \\|\\| is always false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/listener/BreakBlockListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Result of \\|\\| is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/listener/BreakBlockListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Right side of \\|\\| is always true\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/src/listener/BreakBlockListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Unreachable statement \\- code above always terminates\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/listener/BreakBlockListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Negated boolean expression is always false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternExpansive.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly invalid array key type jasonw4331\\\\VeinMiner\\\\utils\\\\NamespacedKey\\|string\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternRegistry.php',
];
$ignoreErrors[] = [
	'message' => '#^Negated boolean expression is always false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternThorough.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$iterator of class CachingIterator constructor expects TIterator of Iterator\\<TKey, TValue\\>, Ramsey\\\\Collection\\\\Set\\<pocketmine\\\\block\\\\Block\\> given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternThorough.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:DOWN\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:EAST\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:NORTH\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:NORTH_EAST\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:NORTH_WEST\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:SOUTH\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:SOUTH_EAST\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:SOUTH_WEST\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:UP\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VeinMiner\\\\api\\\\VBlockFace\\:\\:WEST\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Left side of \\|\\| is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\pattern\\\\PatternUtils\\:\\:getFacesToMine\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\pattern\\\\PatternUtils\\:\\:isOfType\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\pattern\\\\PatternUtils\\:\\:\\$LIMITED_FACES has no type specified\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Right side of && is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Ternary operator condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/pattern/PatternUtils.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplate\\:\\:contains\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot call method remove\\(\\) on array\\<jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplate\\>\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:__construct\\(\\) has parameter \\$blockList with no value type specified in iterable type jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:__construct\\(\\) has parameter \\$tools with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:containsTool\\(\\) should return true but returns false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:getAll\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:getBlockList\\(\\) return type has no value type specified in iterable type jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:getWithTemplate\\(\\) should return array\\<jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\|null\\> but returns array\\<int, jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\|jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplate\\>\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:hasPermission\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Only booleans are allowed in a negated boolean, int\\|false given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^PHPDoc tag @param for parameter \\$tools with type array\\<jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplate\\>\\|null is not subtype of native type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^PHPDoc type for property jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:\\$tools with type array\\<jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplate\\>\\|null is not subtype of native type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VeinMiner\\\\tool\\\\ToolCategory\\:\\:\\$blockList type has no value type specified in iterable type jasonw4331\\\\VeinMiner\\\\data\\\\BlockList\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolCategory.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplateItemStack\\:\\:__construct\\(\\) has parameter \\$lore with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolTemplateItemStack.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplateItemStack\\:\\:getLore\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolTemplateItemStack.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplateItemStack\\:\\:matches\\(\\) should return true but returns bool\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/tool/ToolTemplateItemStack.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\tool\\\\ToolTemplateItemStack\\:\\:matches\\(\\) should return true but returns false\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/tool/ToolTemplateItemStack.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\utils\\\\ItemValidator\\:\\:isValid\\(\\) should return true but returns bool\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/utils/ItemValidator.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot call method getName\\(\\) on pocketmine\\\\plugin\\\\Plugin\\|string\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/utils/NamespacedKey.php',
];
$ignoreErrors[] = [
	'message' => '#^Constant jasonw4331\\\\VeinMiner\\\\utils\\\\NamespacedKey\\:\\:VALID_KEY is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/utils/NamespacedKey.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VeinMiner\\\\utils\\\\VMEventFactory\\:\\:callPlayerVeinMineEvent\\(\\) has parameter \\$blocks with generic class Ramsey\\\\Collection\\\\Set but does not specify its types\\: T$#',
	'count' => 1,
	'path' => __DIR__ . '/src/utils/VMEventFactory.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
