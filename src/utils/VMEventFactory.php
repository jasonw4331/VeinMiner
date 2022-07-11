<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\utils;

use jasonwynn10\VeinMiner\api\event\PlayerVeinMineEvent;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use jasonwynn10\VeinMiner\pattern\VeinMiningPattern;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use Ramsey\Collection\Set;

final class VMEventFactory{

	private function __construct(){}

	public static function callPlayerVeinMineEvent(Player $player, VeinBlock $type, Item $item, ToolCategory $category, Set $blocks, VeinMiningPattern $pattern) : PlayerVeinMineEvent {
		$event = new PlayerVeinMineEvent($player, $type, $item, $category, $blocks, $pattern);
		$event->call();
		return $event;
	}

}