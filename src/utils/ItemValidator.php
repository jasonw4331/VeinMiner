<?php
declare(strict_types=1);

namespace jasonw4331\VeinMiner\utils;

use jasonw4331\VeinMiner\tool\ToolCategory;
use jasonw4331\VeinMiner\tool\ToolTemplate;
use pocketmine\item\Item;
use function array_filter;
use function count;

final class ItemValidator{
	private function __construct(){ }

	/**
	 * Check whether or not the provided item is valid according to the provided category's
	 * available item templates.
	 *
	 * @param Item         $item     the item to check
	 * @param ToolCategory $category the category to check
	 *
	 * @return true if valid, false otherwise
	 */
	public static function isValid(Item $item, ToolCategory $category) : bool{
		if($item->isNull()){
			return $category === ToolCategory::$HAND;
		}

		return count(array_filter($category->getTools(), static fn(ToolTemplate $template) => $template->matches($item))) > 0;
	}
}
