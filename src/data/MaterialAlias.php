<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\data;

use Ds\Set;
use IteratorAggregate;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;

final class MaterialAlias implements IteratorAggregate{

	/** @var Set<VeinBlock> $blocks */
	private Set $blocks;

	/**
	 * Construct a new alias between varying vein blocks.
	 *
	 * @param VeinBlock ...$blocks the blocks to alias
	 */
	public function __construct(VeinBlock ...$blocks){
		$this->blocks = new Set($blocks);
	}

	/**
	 * Add a block to this alias.
	 *
	 * @param VeinBlock $block the block to add
	 */
	public function addAlias(VeinBlock $block){
		$this->blocks->add($block);
	}

	/**
	 * Remove a VeinBlock from this alias.
	 *
	 * @param VeinBlock $block the block to remove
	 */
	public function removeAlias(VeinBlock $block){
		$this->blocks->add($block);
	}

	/**
	 * Check whether a block is aliased under this material alias.
	 *
	 * @param VeinBlock|BlockIdentifier|Block $block the block to check
	 *
	 * @return true if aliased, false otherwise
	 */
	public function isAliased(VeinBlock|BlockIdentifier|Block $block) : bool{
		if($block instanceof VeinBlock){
			return $this->blocks->contains($block);
		}elseif($block instanceof BlockIdentifier){
			return count(array_filter($this->blocks, static fn(VeinBlock $b) => $b->encapsulates($block))) > 0;
		}
		return !$block instanceof Air && $this->isAliased($block->getIdInfo());
	}

	/**
	 * Get all blocks that are considered under this alias. A copy of the set is returned,
	 * therefore any changes made to the returned set will not affect this MaterialAlias.
	 *
	 * @return VeinBlock[] all aliased blocks
	 */
	public function getAliasedBlocks() : array {
		return $this->blocks->toArray();
	}

	public function getIterator() : array {
		return $this->blocks->toArray();
	}

	public function __clone() {
		return new self(...$this->blocks->toArray());
	}
}