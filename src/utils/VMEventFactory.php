<?php
declare(strict_types=1);

namespace jasonw4331\VeinMiner\utils;

use jasonw4331\VeinMiner\api\event\PlayerVeinMineEvent;
use jasonw4331\VeinMiner\data\block\VeinBlock;
use jasonw4331\VeinMiner\pattern\VeinMiningPattern;
use jasonw4331\VeinMiner\tool\ToolCategory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use Ramsey\Collection\Set;

final class VMEventFactory{

	private function __construct(){ }

	public static function callPlayerVeinMineEvent(Player $player, VeinBlock $type, Item $item, ToolCategory $category, Set $blocks, VeinMiningPattern $pattern) : PlayerVeinMineEvent{
		$event = new PlayerVeinMineEvent($player, $type, $item, $category, $blocks, $pattern);
		$event->call();
		return $event;
	}

}
