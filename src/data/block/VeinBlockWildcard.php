<?php
declare(strict_types=1);

namespace jasonw4331\VeinMiner\data\block;

use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\SingletonTrait;

class VeinBlockWildcard extends VeinBlock{
	use SingletonTrait;

	/**
	 * @inheritDoc
	 */
	public function getType() : BlockIdentifier{
		return VanillaBlocks::AIR()->getIdInfo();
	}

	/**
	 * @inheritDoc
	 */
	public function hasSpecificData() : bool{
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function getBlockData() : BlockIdentifier{
		return VanillaBlocks::AIR()->getIdInfo();
	}

	/**
	 * @inheritDoc
	 */
	public function encapsulates(BlockIdentifier|Block $block) : bool{
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function asDataString() : string{
		return "*";
	}

	public function isWildcard() : bool{
		return true;
	}
}
