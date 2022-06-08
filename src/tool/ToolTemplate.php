<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\tool;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use pocketmine\item\Item;

/**
 * Represents a template for a {@link ToolCategory}'s vein mining tool. The {@link ItemStack}
 * used to vein mine must match this template (such that {@link #matches(ItemStack)} is true).
 *
 * @author Parker Hawke - 2008Choco
 */
abstract class ToolTemplate{

	/**
	 * Check whether or not the provided item matches this template.
	 *
	 * @param Item $item the item to check
	 *
	 * @return true if matches, false otherwise
	 */
	public abstract function matches(Item $item) : bool;

	/**
	 * Get the algorithm config for this tool template. This template should have precedence
	 * over the its category algorithm config as well as the global algorithm config.
	 *
	 * @return AlgorithmConfig|null the algorithm config
	 */
	public abstract function getConfig() : ?AlgorithmConfig;

	/**
	 * Get the category from which this tool template resides.
	 *
	 * @return ToolCategory the belonging tool category
	 */
	public abstract function getCategory() : ToolCategory;

	/**
	 * Get this template as a {@literal Predicate<ItemStack>}
	 *
	 * @return callable the template predicate
	 */
	public function asPredicate() : callable {
		return [$this, 'matches'];
	}

}