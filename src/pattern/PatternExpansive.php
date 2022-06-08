<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use jasonwynn10\VeinMiner\data\MaterialAlias;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use jasonwynn10\VeinMiner\tool\ToolTemplate;
use jasonwynn10\VeinMiner\utils\NamespacedKey;
use pocketmine\block\Block;
use pocketmine\utils\SingletonTrait;

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

	private array $buffer = [];
	private array $recent = [];
	private NamespacedKey $key;

	private function __construct(){
		$this->key = new NamespacedKey(VeinMiner::getInstance(), 'expansive');
	}

	public function allocateBlocks(array &$blocks, VeinBlock $type, Block $origin, ToolCategory $category, ?ToolTemplate $template, AlgorithmConfig $algorithmConfig, ?MaterialAlias $alias = null) : void{
		$this->recent[] = $origin; // For first iteration

		$maxVeinSize = $algorithmConfig->getMaxVeinSize();
		$facesToMine = PatternUtils::getFacesToMine($algorithmConfig);

		while(count($blocks) < $maxVeinSize) {
			foreach($this->recent as $current) {
				foreach($facesToMine as $face) {
					$relative = $face->getRelative($current);
					if(in_array($relative, $blocks, true) || !PatternUtils::isOfType($type, $origin, $alias, $relative)) {
						continue;
					}

					if(count($blocks) + count($this->buffer) >= $maxVeinSize) {
						continue 2;
					}

					$this->buffer[] = $relative;
				}
			}

			if(count($this->buffer) === 0) {
				break;
			}

			$this->recent = $this->buffer;
			array_push($blocks, ...$this->buffer);
			$this->buffer = [];
		}

		$this->recent = [];
	}

	public function getKey() : NamespacedKey{
		return $this->key;
	}
}