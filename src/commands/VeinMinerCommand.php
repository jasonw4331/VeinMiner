<?php
declare(strict_types=1);
namespace jasonwynn10\VeinMiner\commands;

use jasonwynn10\VeinMiner\api\ActivationStrategy;
use jasonwynn10\VeinMiner\data\PlayerPreferences;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use jasonwynn10\VeinMiner\tool\ToolTemplateItemStack;
use jasonwynn10\VeinMiner\utils\NamespacedKey;
use jasonwynn10\VeinMiner\utils\VMConstants;
use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\item\ItemBlock;
use pocketmine\item\StringToItemParser;
use pocketmine\item\Tool;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;
use pocketmine\utils\TextFormat;

final class VeinMinerCommand implements CommandExecutor, PluginOwned{
	use PluginOwnedTrait;

	/**
	 * @inheritDoc
	 */
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if (\count($args) < 1) {
			throw new InvalidCommandSyntaxException();
		}

		// reload subcommand
		if ($args[0] === 'reload') {
			if (!$command->testPermission($sender, VMConstants::PERMISSION_RELOAD)) {
				return true;
			}
			$this->owningPlugin->getConfig()->reload();
			$this->owningPlugin->getCategoriesConfig()->reload();

			// Clear data from memory
			ToolCategory::clearCategories();
			$manager = $this->owningPlugin->getVeinMinerManager();
			$manager->clearLocalisedData();

			// Load data into memory
			$manager->loadToolCategories();
			$manager->loadVeinableBlocks();
			$manager->loadMaterialAliases();
			$manager->loadDisabledGameModes();

			$sender->sendMessage(TextFormat::GREEN . 'VeinMiner configuration successfully reloaded.');
			return true;
		}

		// version subcommand
		elseif ($args[0] === 'version') {
			$description = $this->owningPlugin->getDescription();
			$headerFooter = TextFormat::GOLD . TextFormat::BOLD . TextFormat::STRIKETHROUGH . \str_repeat('-', 44);

			$sender->sendmessage($headerFooter);
			$sender->sendmessage('');
			$sender->sendMessage(TextFormat::GOLD . 'Version: ' . TextFormat::WHITE . $description->getVersion() . $this->getUpdateSuffix());
			$sender->sendMessage(TextFormat::GOLD . 'Developer: ' . TextFormat::WHITE . $description->getAuthors()[0]);
			$sender->sendMessage(TextFormat::GOLD . 'Plugin page: ' . TextFormat::WHITE . $description->getWebsite());
			$sender->sendMessage(TextFormat::GOLD . 'Report bugs to: ' . TextFormat::WHITE . 'https://github.com/jasonwynn10/VeinMiner/issues');
			$sender->sendmessage('');
			$sender->sendmessage($headerFooter);
		}

		// Toggle subcommand
		elseif ($args[0] === 'toggle') {
			if (!$sender instanceof Player) {
				$sender->sendMessage('That command can only be used in-game.');
				return true;
			}
			if (!$this->canVeinMine($sender)) {
				$sender->sendMessage(TextFormat::RED . 'You may not toggle a feature to which you do not have access.');
				return true;
			}
			if (!$command->testPermission($sender, VMConstants::PERMISSION_TOGGLE)) {
				return true;
			}
			$playerData = PlayerPreferences::get($sender);
			// Toggle a specific tool
			if (\count($args) >= 2) {
				$category = ToolCategory::get($args[1]);
				if ($category === null) {
					$sender->sendMessage(TextFormat::GRAY . 'Invalid tool category: ' . TextFormat::YELLOW . $args[1]);
					return true;
				}

				$playerData->setVeinMinerEnabled(!$playerData->isVeinMinerEnabled(), $category);
				$sender->sendMessage(TextFormat::GRAY . 'VeinMiner successfully toggled '
					. ($playerData->isVeinMinerDisabled($category) ? TextFormat::RED . 'off' : TextFormat::GREEN . 'on')
					. TextFormat::GRAY . ' for tool ' . TextFormat::YELLOW . \mb_strtolower($category->getId()) . TextFormat::GRAY . '.');
			}

			// Toggle all tools
			else {
				$playerData->setVeinMinerEnabled(!$playerData->isVeinMinerEnabled());
				$sender->sendMessage(TextFormat::GRAY . 'VeinMiner successfully toggled '
					. ($playerData->isVeinMinerEnabled() ? TextFormat::GREEN . 'on' : TextFormat::RED . 'off')
					. TextFormat::GRAY . ' for ' . TextFormat::YELLOW . 'all tools');
			}
		}

		// Mode subcommand
		elseif($args[0] === 'mode'){
			if (!$sender instanceof Player) {
				$sender->sendMessage('That command can only be used in-game.');
				return true;
			}
			if (!$this->canVeinMine($sender)) {
				$sender->sendMessage(TextFormat::RED . 'You may not toggle a feature to which you do not have access.');
				return true;
			}
			if(!$command->testPermission($sender, VMConstants::PERMISSION_MODE)){
				return true;
			}
			if(\count($args) < 2){
				$sender->sendMessage(TextFormat::RED . 'Invalid command syntax! ' . TextFormat::GRAY . 'Missing parameter(s). ' . TextFormat::YELLOW . '/' . $label . ' ' . $args[0] . ' <sneak|stand|always|client>');
				return true;
			}
			try{
				/** @var ActivationStrategy $strategy */
				$strategy = \call_user_func([ActivationStrategy::class, \strtoupper($args[1])]);
			}catch(\InvalidArgumentException $e) {
				$sender->sendMessage(TextFormat::GRAY . 'Invalid activation strategy: ' . TextFormat::YELLOW . $args[1] . TextFormat::GRAY . '.');
				return true;
			}
			PlayerPreferences::get($sender)->setActivationStrategy($strategy);
			$sender->sendMessage(TextFormat::GREEN . 'Activation mode successfully changed to ' . TextFormat::YELLOW . $args[1] . TextFormat::GREEN . '.');
		}

		// Blocklist subcommand
		elseif($args[0] === 'blocklist') {
			if(\count($args) < 2) {
				$sender->sendMessage(TextFormat::RED . 'Invalid command syntax! ' . TextFormat::GRAY . 'Missing parameter(s). ' . TextFormat::YELLOW . '/' . $label . ' ' . $args[0] . ' <category> <add|remove|list>');
				return true;
			}

			$category = ToolCategory::get($args[1]);
			if ($category === null) {
				$sender->sendMessage(TextFormat::GRAY . "Invalid tool category: " . TextFormat::YELLOW . $args[1] . TextFormat::GRAY . ".");
				return true;
			}

			if (\count($args) < 3) {
				$sender->sendMessage(TextFormat::RED . "Invalid command syntax! " . TextFormat::GRAY . "Missing parameter(s). " . TextFormat::YELLOW . "/" . $label . " " . $args[0] . " " . $args[1] . " <add|remove|list>");
				return true;
			}

			// /veinminer blocklist <category> add
			if($args[2] === 'add') {
				if(!$command->testPermissionSilent($sender, VMConstants::PERMISSION_BLOCKLIST_ADD)) {
					$sender->sendMessage(TextFormat::RED . 'You do not have permission to add blocks to the blocklist.');
					return true;
				}

				if (\count($args) < 4) {
					$sender->sendMessage(TextFormat::RED . "Invalid command syntax! " . TextFormat::GRAY . "Missing parameter(s). " . TextFormat::YELLOW . "/" . $label . " " . $args[0] . " " . $args[1] . " add <block>[data]");
					return true;
				}

				$blocklist = $category->getBlocklist();
				$configBlocklist = $this->owningPlugin->getConfig()->getNested("BlockList." . $category->getId());

				for ($i = 3; $i < \count($args); ++$i) {
					$blockArg = \strtolower($args[$i]);
					$item = StringToItemParser::getInstance()->parse($args[$i]);
					if (!$item instanceof ItemBlock) {
						$sender->sendMessage(TextFormat::RED . "Unknown block type/block state (was it an item)? " . TextFormat::GRAY . "Given " . TextFormat::YELLOW . $blockArg . TextFormat::GRAY . ".");
						continue;
					}
					$block = $item->getBlock();

					if ($blocklist->contains($block)) {
						$sender->sendMessage(TextFormat::GRAY . "A block with the ID " . TextFormat::YELLOW . $blockArg . TextFormat::GRAY . " is already on the " . TextFormat::YELLOW . $category->getId() . " " . TextFormat::GRAY . " blocklist.");
						continue;
					}

					$blocklist->add($block);
					$configBlocklist->add($block);
					$this->owningPlugin->getConfig()->setNested("BlockList." . $category->getId(), $configBlocklist);

					$sender->sendMessage(TextFormat::GRAY . "Block ID " . $block->__toString() . TextFormat::GRAY . " successfully added to the blocklist.");
				}

				$this->owningPlugin->saveConfig();
			}

			// /veinminer blocklist <category> remove
			elseif($args[2] === 'remove'){
				if(!$command->testPermissionSilent($sender, VMConstants::PERMISSION_BLOCKLIST_REMOVE)){
					$sender->sendMessage(TextFormat::RED . 'You do not have permission to remove blocks from the blocklist.');
					return true;
				}

				if(\count($args) < 4){
					$sender->sendMessage(TextFormat::RED . "Invalid command syntax! " . TextFormat::GRAY . "Missing parameter(s). " . TextFormat::YELLOW . "/" . $label . " " . $args[0] . " " . $args[1] . " remove <block>[data]");
					return true;
				}

				$blocklist = $category->getBlocklist();
				$configBlocklist = $this->owningPlugin->getConfig()->getNested("BlockList." . $category->getId());

				for($i = 3; $i < \count($args); ++$i){
					$blockArg = \strtolower($args[$i]);
					$item = StringToItemParser::getInstance()->parse($args[$i]);
					if(!$item instanceof ItemBlock){
						$sender->sendMessage(TextFormat::RED . "Unknown block type/block state (was it an item)? " . TextFormat::GRAY . "Given " . TextFormat::YELLOW . $blockArg . TextFormat::GRAY . ".");
						continue;
					}
					$block = $item->getBlock();

					if(!$blocklist->contains($block)){
						$sender->sendMessage(TextFormat::GRAY . "No block with the ID " . TextFormat::YELLOW . $blockArg . TextFormat::GRAY . " was found on the " . TextFormat::YELLOW . $category->getId() . " " . TextFormat::GRAY . " blocklist.");
						continue;
					}

					$blocklist->add($block);
					$configBlocklist->add($block);
					$this->owningPlugin->getConfig()->setNested("BlockList." . $category->getId(), $configBlocklist);

					$sender->sendMessage(TextFormat::GRAY . "Block ID " . $block->__toString() . TextFormat::GRAY . " successfully removed from the blocklist.");
				}

				$this->owningPlugin->saveConfig();
			}

			// /veinminer blocklist <category> list
			elseif($args[2] === 'list'){
				if(!$command->testPermission($sender, VMConstants::PERMISSION_BLOCKLIST_LIST . '.' . \str_replace('_', ' ', \strtolower($category->getId())))){
					return true;
				}

				/** @var Block[] $blocklistIterable */
				$blocklistIterable = $category->getBlocklist();
				if ($this->owningPlugin->getConfig()->get(VMConstants::CONFIG_SORT_BLOCKLIST_ALPHABETICALLY, true)) {
					\sort($blocklistIterable);
				}

				if (\count($blocklistIterable) < 1) {
					$sender->sendMessage(TextFormat::YELLOW . "The " . $category->getId() . " category is empty.");
					return true;
				}

				$sender->sendMessage("");
				$sender->sendMessage(TextFormat::GREEN . "Block list " . TextFormat::GRAY . "for category " . TextFormat::GREEN . \str_replace('_', ' ', \strtolower($category->getId())) . TextFormat::GRAY . ":");
				forEach($blocklistIterable as $block) {
					$sender->sendMessage(TextFormat::WHITE . " - " . $block->__toString());
				}
				$sender->sendMessage("");
			}

			// Unknown parameter
			else {
				$sender->sendMessage(TextFormat::RED . "Invalid command syntax!" . TextFormat::GRAY . " Unknown parameter: " . TextFormat::AQUA . $args[2] . TextFormat::GRAY . ". " . TextFormat::YELLOW . "/" . $label . " " . $args[0] . " " . $args[1] . " <add|remove|list>");
				return true;
			}
		}

		elseif($args[0] === 'toollist') {
			if(\count($args) < 2){
				$sender->sendMessage(TextFormat::RED . "Invalid command syntax! " . TextFormat::GRAY . "Missing parameter(s). " . TextFormat::YELLOW . "/" . $label . " " . $args[0] . " <category> <add|remove|list>");
				return true;
			}

			$category = ToolCategory::get($args[1]);
			if ($category === null) {
				$sender->sendMessage(TextFormat::GRAY . "Invalid tool category: " . TextFormat::YELLOW . $args[1] . TextFormat::GRAY . ".");
				return true;
			}

			if(\count($args) < 3){
				$sender->sendMessage(TextFormat::RED . "Invalid command syntax! " . TextFormat::GRAY . "Missing parameter(s). " . TextFormat::YELLOW . "/" . $label . " " . $args[0] . " " . $args[1] . " <add|remove|list>");
				return true;
			}

			// /veinminer toollist <category> add
			if($args[2] === 'add'){
				if(!$command->testPermission($sender, VMConstants::PERMISSION_TOOLLIST_ADD)){
					return true;
				}

				if ($category->equals(ToolCategory::$HAND)) {
					$sender->sendMessage(TextFormat::RED . "The hand category cannot be modified");
					return true;
				}

				if(\count($args) < 4){
					$sender->sendMessage(TextFormat::RED . "Invalid command syntax! " . TextFormat::GRAY . "Missing parameter(s). " . TextFormat::YELLOW . "/" . $label . " " . $args[0] . " " . $args[1] . " " . $args[2] . " <tool>[data]");
					return true;
				}

				$categoriesConfig = $categoriesConfigWrapper = $this->owningPlugin->getCategoriesConfig();
				$configToolList = $categoriesConfig->getNested($category->getId() . ".Items", []);

				if ($configToolList === null) {
					$sender->sendMessage(TextFormat::RED . "Something went wrong... is the " . TextFormat::YELLOW . "categories.yml " . TextFormat::GRAY . "formatted properly?");
					return true;
				}

				for($i = 3; $i < \count($args); ++$i){
					$toolArg = \strtolower($args[$i]);
					$tool = StringToItemParser::getInstance()->parse($args[$i]);
					if(!$tool instanceof Tool){
						$sender->sendMessage(TextFormat::RED . "Unknown item. " . TextFormat::GRAY . "Given: " . TextFormat::YELLOW . $toolArg . TextFormat::GRAY . ".");
						continue;
					}

					if ($category->containsTool($tool)) {
						$sender->sendMessage(TextFormat::GRAY . "An item with the ID " . TextFormat::YELLOW . $toolArg . TextFormat::GRAY . " is already on the " . TextFormat::YELLOW . $category->getId() . TextFormat::GRAY . " tool list.");
						continue;
					}

					$configToolList->add($tool->getName());
					$category->addTool(new ToolTemplateItemStack($category, $tool));
					$categoriesConfig->set($category->getId() . ".Items", $configToolList);

					$sender->sendMessage(TextFormat::GRAY . "Item ID " . TextFormat::YELLOW . $tool->getName() . TextFormat::GRAY . " successfully added to the " . TextFormat::YELLOW . $category->getId() . TextFormat::GRAY . " tool list.");
				}

				$categoriesConfigWrapper->save();
				$categoriesConfigWrapper->reload();
			}

			// /veinminer toollist <category> remove
			elseif($args[2] === 'remove'){
				if(!$command->testPermission($sender, VMConstants::PERMISSION_TOOLLIST_REMOVE)){
					return true;
				}

				if ($category->equals(ToolCategory::$HAND)) {
					$sender->sendMessage(TextFormat::RED . "The hand category cannot be modified");
					return true;
				}

				if(\count($args) < 4){
					$sender->sendMessage(TextFormat::RED . "Invalid command syntax! " . TextFormat::GRAY . "Missing parameter(s). " . TextFormat::YELLOW . "/" . $label . " " . $args[0] . " " . $args[1] . " " . $args[2] . " <tool>[data]");
					return true;
				}

				$categoriesConfig = $categoriesConfigWrapper = $this->owningPlugin->getCategoriesConfig();
				$configToolList = $categoriesConfig->getNested($category->getId() . ".Items", []);

				if ($configToolList === null) {
					$sender->sendMessage(TextFormat::RED . "Something went wrong... is the " . TextFormat::YELLOW . "categories.yml " . TextFormat::GRAY . "formatted properly?");
					return true;
				}

				for($i = 3; $i < \count($args); ++$i){
					$toolArg = \strtolower($args[$i]);
					$tool = StringToItemParser::getInstance()->parse($args[$i]);
					if(!$tool instanceof Tool){
						$sender->sendMessage(TextFormat::RED . "Unknown item. " . TextFormat::GRAY . "Given: " . TextFormat::YELLOW . $toolArg . TextFormat::GRAY . ".");
						continue;
					}

					if (!$category->containsTool($tool)) {
						$sender->sendMessage(TextFormat::GRAY . "An item with the ID " . TextFormat::YELLOW . $toolArg . TextFormat::GRAY . " is not on the " . TextFormat::YELLOW . $category->getId() . TextFormat::GRAY . " tool list.");
						continue;
					}

					$configToolList->remove($tool->getName());
					$category->removeTool(new ToolTemplateItemStack($category, $tool));
					$categoriesConfig->set($category->getId() . ".Items", $configToolList);

					$sender->sendMessage(TextFormat::GRAY . "Item ID " . TextFormat::YELLOW . $tool->getName() . TextFormat::GRAY . " successfully added to the " . TextFormat::YELLOW . $category->getId() . TextFormat::GRAY . " tool list.");
				}

				$categoriesConfigWrapper->save();
				$categoriesConfigWrapper->reload();
			}

			// /veinminer toollist <category> list
			elseif($args[2] === 'list'){
				if(!$command->testPermission($sender, VMConstants::PERMISSION_TOOLLIST_LIST . '.' . \str_replace('_', ' ', \strtolower($category->getId())))){
					return true;
				}

				$sender->sendMessage("");
				$sender->sendMessage(TextFormat::GREEN . "Tool list " . TextFormat::GRAY . "for category " . TextFormat::GREEN . \str_replace('_', ' ', \strtolower($category->getId())) . TextFormat::GRAY . ":");
				foreach($category->getTools() as $tool) {
					$sender->sendMessage(TextFormat::WHITE . " - " . TextFormat::YELLOW . $tool);
				}
				$sender->sendMessage("");
			}
		}

		elseif($args[0] === 'pattern') {
			if(!$command->testPermission($sender, VMConstants::PERMISSION_PATTERN)){
				return true;
			}

			if(\count($args) < 2) {
				$sender->sendMessage(TextFormat::RED . "Invalid command syntax! " . TextFormat::GRAY . "Missing parameter(s). " . TextFormat::YELLOW . "/" . $label . " " . $args[0] . " <pattern_id>");
				return true;
			}

			try{
				$patternNamespace = new NamespacedKey($this->owningPlugin, $args[1]);
			}catch(\OutOfBoundsException $e) {
				$sender->sendMessage(TextFormat::RED . "Invalid pattern ID! " . TextFormat::GRAY . "Pattern IDs should be formatted as " . TextFormat::YELLOW . "namespace:id" . TextFormat::GRAY . "(i.e. " . TextFormat::YELLOW . "veinminer:expansive" . TextFormat::GRAY . ").");
				return true;
			}

			$pattern = $this->owningPlugin->getPatternRegistry()->getPattern((string) $patternNamespace);
			if ($pattern === null) {
				$sender->sendMessage(TextFormat::GRAY . "A pattern with the ID " . TextFormat::YELLOW . $patternNamespace . TextFormat::GRAY . " could not be found.");
				return true;
			}

			$this->owningPlugin->setVeinMiningPattern($pattern);
			$this->owningPlugin->getConfig()->set(VMConstants::CONFIG_VEIN_MINING_PATTERN, $pattern->getKey());
			$this->owningPlugin->saveConfig();

			$sender->sendMessage(TextFormat::GREEN . "Patterns successfully set to " . TextFormat::YELLOW . $patternNamespace . TextFormat::GRAY . ".");
		}

		// Unknown command usage
		else {
			throw new InvalidCommandSyntaxException();
		}

		return true;
	}

	private function hasBlocklistPerms(CommandSender $sender) : bool {
		return $sender->hasPermission(VMConstants::PERMISSION_BLOCKLIST_ADD)
			|| $sender->hasPermission(VMConstants::PERMISSION_BLOCKLIST_REMOVE)
			|| $sender->hasPermission(VMConstants::PERMISSION_BLOCKLIST_LIST . '.*');
	}

	private function hasToolListPerms(CommandSender $sender) : bool {
		return $sender->hasPermission(VMConstants::PERMISSION_TOOLLIST_ADD)
			|| $sender->hasPermission(VMConstants::PERMISSION_TOOLLIST_REMOVE)
			|| $sender->hasPermission(VMConstants::PERMISSION_TOOLLIST_LIST . '.*');
	}

	private function canVeinMine(Player $player) : bool{
		foreach (ToolCategory::getAll() as $category) {
			if ($category->hasPermission($player)) {
				return true;
			}
		}

		return false;
	}

	private function getUpdateSuffix() : string {
		if ($this->owningPlugin->getConfig()->get(VMConstants::CONFIG_PERFORM_UPDATE_CHECKS, true) === false) {
			return '';
		}

		$result = UpdateChecker::get()->getLastResult();
		return ($result != null && $result->requiresUpdate()) ? ' (' . TextFormat::GREEN . TextFormat::BOLD . 'UPDATE AVAILABLE!' . TextFormat::GRAY . ')' : '';
	}
}