<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\api\event;

use jasonwynn10\VeinMiner\data\block\VeinBlock;
use jasonwynn10\VeinMiner\pattern\VeinMiningPattern;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use pocketmine\item\Item;
use pocketmine\player\Player;
use Ramsey\Collection\Set;

final class PlayerVeinMineEvent extends Event implements Cancellable{
	use CancellableTrait;

	public function __construct(private Player $player, private VeinBlock $type, private Item $item, private ToolCategory $category, private Set $blocks, private VeinMiningPattern $pattern){ }

	public function getBlocks() : Set{
		return $this->blocks;
	}

	public function getAffectedBlock() : VeinBlock{
		return $this->type;
	}

	public function getItem() : Item{
		return $this->item;
	}

	public function getCategory() : ToolCategory{
		return $this->category;
	}

	public function getPattern() : VeinMiningPattern{
		return $this->pattern;
	}
}
