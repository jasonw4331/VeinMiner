<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\pattern;

use jasonwynn10\VeinMiner\api\VBlockFace;
use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use jasonwynn10\VeinMiner\data\MaterialAlias;
use pocketmine\block\Block;

final class PatternUtils{
	private static $LIMITED_FACES = [];

	public static function setup() : void {
		self::$LIMITED_FACES = [
			VBlockFace::UP(),
			VBlockFace::DOWN(),
			VBlockFace::NORTH(),
			VBlockFace::SOUTH(),
			VBlockFace::EAST(),
			VBlockFace::WEST(),
			VBlockFace::NORTH_EAST(),
			VBlockFace::NORTH_WEST(),
			VBlockFace::SOUTH_EAST(),
			VBlockFace::SOUTH_WEST(),
		];
	}

	/**
	 * Check if a block is encapsulated by the VeinBlock type or considered aliased under
	 * the provided alias (if present).
	 *
	 * @param VeinBlock $type the type for which to check
	 * @param Block|null $origin the origin type
	 * @param MaterialAlias|null $alias the alias. null if no alias
	 * @param Block $block the block to validate
	 *
	 * @return true if the provided block is of that type or aliased, false otherwise
	 */
	public static function isOfType(VeinBlock $type, ?Block $origin, ?MaterialAlias $alias, Block $block) : bool{
		if ($origin !== null && $type->isWildcard()) {
			return $origin->isSameType($block);
		}

		return $type->encapsulates($block) || ($alias != null && $alias->isAliased($block));
	}

	/**
	 * Get an array of VBlockFaces to mine based on VeinMiner's "IncludeEdges" configuration.
	 *
	 * @param AlgorithmConfig $algorithmConfig the algorithm configuration
	 *
	 * @return array the block face array
	 */
	public static function getFacesToMine(AlgorithmConfig $algorithmConfig) : array {
		return $algorithmConfig->includesEdges() ? VBlockFace::getAll() : self::$LIMITED_FACES;
	}

}