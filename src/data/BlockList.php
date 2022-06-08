<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\data;

use Ds\Set;
use IteratorAggregate;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use pocketmine\block\BlockIdentifier;

/**
 * Represents a list of blocks and states (see {@link VeinBlock}).
 *
 * @author Parker Hawke - 2008Choco
 */
final class BlockList implements IteratorAggregate,\Countable {

	/** @var Set<VeinBlock> $blocks */
	private Set $blocks;

	/**
	 * Create a new BlockList with the values from a set of existing {@link BlockList} instances.
	 * Duplicate instances of blocks and states will not be included.
	 *
	 * @param BlockList ...$lists the block lists whose values should be included
	 */
	public function __construct(BlockList ...$lists){
        $this->blocks = new Set();
		foreach($lists as $list) {
			$this->blocks->merge($list);
		}
	}

	/**
	 * Add a {@link BlockData} to this BlockList.
	 *
	 * @param VeinBlock|BlockIdentifier $block the data to add
	 *
	 * @return void the VeinBlock added to this list
	 */
	public function add(VeinBlock|BlockIdentifier $block) : void {
		if($block instanceof BlockIdentifier) {
			$block = VeinBlock::get($block);
		}
		$this->blocks->add($block);
	}

	/**
	 * Add a collection of VeinBlocks to this block list. If a block is already present in this list,
	 * it will be ignored silently.
	 *
	 * @param iterable<VeinBlock> $values the values to add
	 *
	 * @return void if at least one block was added, false otherwise
	 */
	public function addAll(iterable $values) : void {
		foreach($values as $block) {
			$this->add($block);
		}
	}

	/**
	 * Remove a specific VeinBlock from this block list.
	 *
	 * @param VeinBlock|BlockIdentifier $block the block to remove
	 */
	public function remove(VeinBlock|BlockIdentifier $block) : void {
		if($block instanceof BlockIdentifier) {
			$block = VeinBlock::get($block);
		}
		$this->blocks->remove($block);
	}

	/**
	 * Check whether or not this list contains a specific {@link VeinBlock} instance.
	 *
	 * @param VeinBlock|BlockIdentifier $data the block to check
	 *
	 * @return true if present, false otherwise
	 */
	public function contains(VeinBlock|BlockIdentifier $data) : bool {
		if($data instanceof BlockIdentifier) {
			return $this->containsOnPredicate(static fn(VeinBlock $block) => $block->encapsulates($data));
		}
		return $this->blocks->contains($data);
	}

	/**
	 * Check whether or not this list contains exactly the specified {@link BlockData}.
	 *
	 * @param BlockIdentifier $data the data to check
	 *
	 * @return true if present, false otherwise
	 */
	public function containsExact(BlockIdentifier $data) : bool {
		return $this->containsOnPredicate(static fn(VeinBlock $block) => $block->getBlockData()->equals($data));
	}

	/**
	 * Check whether or not this blocklist contains a wildcard.
	 *
	 * @return true if wildcarded, false otherwise
	 */
	public function containsWildcard() : bool {
		return $this->containsOnPredicate(static fn(VeinBlock $block) => $block->isWildcard($block));
	}

	/**
	 * Get the VeinBlock from this {@link BlockList} that encapsulates the given {@link BlockData}.
	 * If no VeinBlock in this list encapsulates the BlockData, (i.e. {@link #contains(BlockData)}
	 * is false) null is returned.
	 *
	 * @param BlockIdentifier $data the data for which to get a VeinBlock
	 *
	 * @return VeinBlock|null the first encapsulating VeinBlock for this list. null if none
	 */
	public function getVeinBlock(BlockIdentifier $data) : ?VeinBlock {
		foreach($this->blocks as $block) {
			if($block->encapsulates($data)) {
				return $block;
			}
		}
		return null;
	}

	private function containsOnPredicate(callable $predicate) : bool {
		foreach($this->blocks as $block) {
			if($predicate($block)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the size of this list.
	 *
	 * @return int the list size
	 */
	public function count() : int {
		return $this->blocks->count();
	}

	/**
	 * Clear the contents of this list.
	 */
	public function clear() : void {
		$this->blocks->clear();
	}

	public function getIterator() : array {
		return $this->blocks->toArray();
	}
}