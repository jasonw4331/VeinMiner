<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\tool;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use jasonwynn10\VeinMiner\data\BlockList;
use jasonwynn10\VeinMiner\utils\VMConstants;
use jasonwynn10\VeinMiner\VeinMiner;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\permission\Permissible;
use function count;
use function in_array;
use function is_string;
use function mb_strtolower;
use function preg_match;
use function str_replace;

final class ToolCategory{

	/** @var array<ToolCategory> $CATEGORIES */
	private static array $CATEGORIES = [];
	private const VALID_ID = '/[A-Za-z0-9]+/i';

	public static ToolCategory $HAND;

	private BlockList $blockList;

	/**
	 * Construct a new tool category with an empty block and tool list.
	 *
	 * @param string                   $id        the unique id of the tool category. Recommended to be a single-worded, PascalCase id.
	 *                                            Must match [A-Za-z0-9]
	 * @param AlgorithmConfig          $config    the algorithm configuration for this category
	 * @param Blocklist|null           $blockList the category block list
	 * @param array<ToolTemplate>|null $tools     the tools that apply to this category
	 */
	public function __construct(private string $id, private AlgorithmConfig $config, ?Blocklist $blockList = null, private array $tools = []){
		static $lock = true;
		if(!isset(ToolCategory::$HAND) && $lock){
			$lock = false;
			ToolCategory::$HAND = new ToolCategory('Hand', VeinMiner::getInstance()->getVeinMinerManager()->getConfig()); // Hand uses the default config
			ToolCategory::register(ToolCategory::$HAND);
		}

		if(!preg_match(ToolCategory::VALID_ID, $id)){
			throw new \InvalidArgumentException("Invalid tool category id: $id");
		}

		if($blockList === null){
			$blockList = new BlockList();
		}
		$this->blockList = $blockList;
	}

	/**
	 * Get the unique id of this tool category.
	 */
	public function getId() : string{
		return $this->id;
	}

	/**
	 * Get the algorithm config for this tool category. This category should have precedence
	 * over the global algorithm config.
	 */
	public function getConfig() : AlgorithmConfig{
		return $this->config;
	}

	/**
	 * Add a tool template to this tool category
	 *
	 * @param ToolTemplate $template the template to add
	 */
	public function addTool(ToolTemplate $template) : void{
		if(in_array($template, $this->tools, true)){
			return;
		}

		$this->tools[] = $template;
	}

	/**
	 * Remove a tool template from this tool category
	 *
	 * @param ToolTemplate|Tool $template the template to remove
	 *
	 * @return true if removed, false otherwise
	 */
	public function removeTool(ToolTemplate|Tool $template) : bool{
		return $this->tools->remove($template);
	}

	/**
	 * Check whether or not the provided item is a part of this category. The item's name
	 * and lore will be taken into consideration.
	 *
	 * @param Tool $item the item to check
	 *
	 * @return true if contained, false otherwise
	 */
	public function containsTool(Tool $item) : bool{
		foreach($this->tools as $template){
			if($template->contains($item)){
				return true;
			}
		}

		return false;
	}

	/**
	 * Get a list of all tool templates that apply to this category. Any changes made to
	 * the returned collection will not reflect upon the category.
	 *
	 * @return array<ToolTemplate>
	 */
	public function getTools() : array{
		return $this->tools;
	}

	/**
	 * Clear all tool templates from this category.
	 */
	public function clearTools() : void{
		$this->tools = [];
	}

	/**
	 * Get the blocklist for this category.
	 */
	public function getBlockList() : BlockList{
		return $this->blockList;
	}

	/**
	 * Check whether or not the given permissible has permission to vein miner using this
	 * tool category.
	 *
	 * @param Permissible $permissible the permissible object to check
	 *
	 * @return true if permission is granted, false otherwise
	 */
	public function hasPermission(Permissible $permissible) : bool{
		return $permissible->hasPermission(str_replace('%s', mb_strtolower($this->id), VMConstants::PERMISSION_DYNAMIC_VEINMINE));
	}

	public function equals(object $obj) : bool{
		return $obj === $this || ($obj instanceof ToolCategory && $obj->getId() === $this->id);
	}

	/**
	 * Get a tool category based on its (case-insensitive) id.
	 *
	 * @param Item|string $id the id of the category to get
	 *
	 * @return ToolCategory|null the tool category. null if none
	 */
	public static function get(Item|string $id) : ?ToolCategory{
		if(is_string($id)){
			return self::$CATEGORIES[mb_strtolower($id)] ?? null;
		}

		if($id->isNull()){
			return ToolCategory::$HAND;
		}

		foreach(ToolCategory::$CATEGORIES as $category){
			foreach($category->getTools() as $tool){
				if($tool->matches($id)){
					return $category;
				}
			}
		}

		return null;
	}

	/**
	 * Register a tool category.
	 *
	 * @param ToolCategory $category the category to register
	 */
	public static function register(ToolCategory $category) : void{
		self::$CATEGORIES[mb_strtolower($category->getId())] = $category;
	}

	/**
	 * Get a tool category based on the provided tool as well as the template against which
	 * the tool was matched. If the tool applies to a category, it will be returned. If more
	 * than one category includes this tool, the category that was registered first will be
	 * returned.
	 *
	 * @param Item $item the item whose category to get
	 *
	 * @return array<ToolCategory|null> the tool category and matched template. null if none
	 */
	public static function getWithTemplate(Item $item) : array{
		if($item->isNull()){
			return [ToolCategory::$HAND, null];
		}

		foreach(self::$CATEGORIES as $category){
			foreach($category->getTools() as $template){
				if($template->matches($item)){
					return [$category, $template];
				}
			}
		}

		return [null, null];
	}

	/**
	 * Get the amount of tool categories registered.
	 *
	 * @return int the amount of registered categories
	 */
	public static function getRegisteredAmount() : int{
		return count(self::$CATEGORIES);
	}

	/**
	 * Get an immutable collection of all registered tool categories.
	 *
	 * @return array all tool categories
	 */
	public static function getAll() : array{
		return self::$CATEGORIES;
	}

	/**
	 * Clear all registered categories.
	 */
	public static function clearCategories() : void{
		self::$CATEGORIES = [];
	}

}
