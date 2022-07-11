<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use jasonwynn10\VeinMiner\data\MaterialAlias;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use jasonwynn10\VeinMiner\tool\ToolTemplate;
use jasonwynn10\VeinMiner\utils\NamespacedKey;
use jasonwynn10\VeinMiner\VeinMiner;
use pocketmine\block\Block;
use pocketmine\utils\SingletonTrait;
use Ramsey\Collection\Set;

/**
 * A {@link VeinMiningPattern} implementation that "expands" to search for similar blocks. Using the
 * outermost layer of blocks in the block list, the search propagates outwards rather than iterating over
 * blocks that have already been identified as surrounded by non-veinmineable (or already allocated)
 * blocks.
 * <p>
 * This implementation is substantially more efficient for larger veins of ores when compared to
 * {@link PatternThorough} though may be overkill for smaller veins of ores. This pattern is still
 * recommended over the aforementioned pattern, however, and as such is used as the default.
 *
 * @author Parker Hawke - 2008Choco
 */
final class PatternExpansive implements VeinMiningPattern{
	use SingletonTrait{
		reset as private reset; // don't let someone delete our instance
		setInstance as private setInstance; // don't let someone set our instance
	}

	private Set $buffer;
	private Set $recent;
	private NamespacedKey $key;

	private function __construct(){
		$this->key = new NamespacedKey(VeinMiner::getInstance(), 'expansive');
		$this->buffer = new Set(Block::class);
		$this->recent = new Set(Block::class);
	}

	public function allocateBlocks(Set $blocks, VeinBlock $type, Block $origin, ToolCategory $category, ?ToolTemplate $template, AlgorithmConfig $algorithmConfig, ?MaterialAlias $alias = null) : void{
		$this->recent->add($origin); // For first iteration

		$maxVeinSize = $algorithmConfig->getMaxVeinSize();
		$facesToMine = PatternUtils::getFacesToMine($algorithmConfig);

		while($blocks->count() < $maxVeinSize) {
			foreach($this->recent as $current) {
				foreach($facesToMine as $face) {
					$relative = $face->getRelative($current);

					if($blocks->contains($relative) || !PatternUtils::isOfType($type, $origin, $alias, $relative)) {
						continue;
					}

					if($blocks->count() + $this->buffer->count() >= $maxVeinSize) {
						continue 2;
					}

					$this->buffer->add($relative);
				}
			}

			if($this->buffer->count() === 0) {
				break;
			}

			$this->recent->clear();
			foreach($this->buffer as $block) {
				$this->recent->add($block);
				$blocks->add($block);
			}
			$this->buffer->clear();
		}

		$this->recent->clear();
	}

	public function getKey() : NamespacedKey{
		return $this->key;
	}
}