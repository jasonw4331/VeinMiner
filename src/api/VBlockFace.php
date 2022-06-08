<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\api;

use pocketmine\block\Block;
use pocketmine\utils\EnumTrait;
use pocketmine\world\Position;

/**
 * @generate-registry-docblock
 */
final class VBlockFace{
	use EnumTrait{
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			// CORE DIRECTIONS
			new self('NORTH', 0, 0, -1),
			new self('SOUTH', 0, 0, 1),
			new self('WEST', -1, 0, 0),
			new self('EAST', 1, 0, 0),
			new self('UP', 0, 1, 0),
			new self('DOWN', 0, -1, 0),

			// CORNER DIRECTIONS
			new self('NORTH_EAST', 1, 0, -1),
			new self('NORTH_WEST', -1, 0, -1),
			new self('SOUTH_EAST', 1, 0, 1),
			new self('SOUTH_WEST', -1, 0, 1),

			// EDGE DIRECTIONS
			new self('NORTH_UP', 0, 1, -1),
			new self('NORTH_DOWN', 0, -1, -1),
			new self('SOUTH_UP', 0, 1, 1),
			new self('SOUTH_DOWN', 0, -1, 1),
			new self('WEST_UP', -1, 1, 0),
			new self('WEST_DOWN', -1, -1, 0),
			new self('EAST_UP', 1, 1, 0),
			new self('EAST_DOWN', 1, -1, 0),

			// CORNER EDGE DIRECTIONS
			new self('NORTH_EAST_UP', 1, 1, -1),
			new self('NORTH_EAST_DOWN', 1, -1, -1),
			new self('NORTH_WEST_UP', -1, 1, -1),
			new self('NORTH_WEST_DOWN', -1, -1, -1),
			new self('SOUTH_EAST_UP', 1, 1, 1),
			new self('SOUTH_EAST_DOWN', 1, -1, 1),
			new self('SOUTH_WEST_UP', -1, 1, 1),
			new self('SOUTH_WEST_DOWN', -1, -1, 1),
		);
	}

	public function __construct(
		string $name,
		private int $xTranslation,
		private int $yTranslation,
		private int $zTranslation
	){
		$this->Enum___construct($name);
	}

	/**
	 * @return int
	 */
	public function getXTranslation() : int{
		return $this->xTranslation;
	}

	/**
	 * @return int
	 */
	public function getYTranslation() : int{
		return $this->yTranslation;
	}

	/**
	 * @return int
	 */
	public function getZTranslation() : int{
		return $this->zTranslation;
	}

	public function getRelative(Position $block) : Block {
		return $block->getWorld()->getBlockAt($block->getX() + $this->xTranslation, $block->getY() + $this->yTranslation, $block->getZ() + $this->zTranslation);
	}

	/* Block views:
     *
     *       Arial:               Front:
     *
     *   NW    N    NE          WU   UP    EU
     *
     *   W   BLOCK   E          W   BLOCK   E
     *
     *   SW    S    ES          WD  DOWN   ED
     *
     */
}