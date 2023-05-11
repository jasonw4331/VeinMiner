<?php
declare(strict_types=1);

namespace jasonw4331\VeinMiner\pattern;

use jasonw4331\VeinMiner\data\AlgorithmConfig;
use jasonw4331\VeinMiner\data\block\VeinBlock;
use jasonw4331\VeinMiner\data\MaterialAlias;
use jasonw4331\VeinMiner\tool\ToolCategory;
use jasonw4331\VeinMiner\tool\ToolTemplate;
use jasonw4331\VeinMiner\utils\NamespacedKey;
use jasonw4331\VeinMiner\VeinMiner;
use pocketmine\block\Block;
use pocketmine\utils\SingletonTrait;
use Ramsey\Collection\Set;
use function count;

/**
 * A {@link VeinMiningPattern} implementation that "pulsates" from the origin outwards. Every
 * iteration, every block starting from the origin will be checks for adjacent blocks.
 * <p>
 * This pattern is less efficient than {@link PatternExpansive} when used for larger veins, but
 * may be more performant when dealing with smaller veins.
 * <p>
 * This pattern should be considered as effectively deprecated. While not literally deprecated,
 * the expansive pattern should be used in place of this as it will yield results more quickly
 * and in an efficient manner.
 *
 * @author Parker Hawke - 2008Choco
 */
final class PatternThorough implements VeinMiningPattern{
	use SingletonTrait {
		reset as private _reset; // don't let someone delete our instance
		setInstance as private _setInstance; // don't let someone set our instance
	}

	/** @var Set<Block> $blockBuffer */
	private Set $blockBuffer;
	private NamespacedKey $key;

	private function __construct(){
		$this->key = new NamespacedKey(VeinMiner::getInstance(), 'thorough');
		$this->blockBuffer = new Set(Block::class);
	}

	public function allocateBlocks(Set $blocks, VeinBlock $type, Block $origin, ToolCategory $category, ?ToolTemplate $template, AlgorithmConfig $algorithmConfig, ?MaterialAlias $alias = null) : void{
		$maxVeinSize = $algorithmConfig->getMaxVeinSize();
		$facesToMine = PatternUtils::getFacesToMine($algorithmConfig);

		while(count($blocks) < $maxVeinSize){
			$trackedBlocks = new \CachingIterator($blocks);
			while($trackedBlocks->hasNext() && $blocks->count() + $this->blockBuffer->count() < $maxVeinSize){
				$trackedBlocks->next();
				$current = $trackedBlocks->current();
				foreach($facesToMine as $face){
					if($blocks->count() + $this->blockBuffer->count() >= $maxVeinSize){
						break;
					}
					$nextBlock = $face->getRelative($current);
					if($blocks->contains($nextBlock) || !PatternUtils::isOfType($type, $origin, $alias, $nextBlock)){
						continue;
					}

					$this->blockBuffer->add($nextBlock);
				}
			}

			if($this->blockBuffer->count() === 0){
				break;
			}

			foreach($this->blockBuffer as $block){
				$blocks->add($block);
			}
			$this->blockBuffer->clear();
		}
	}

	public function getKey() : NamespacedKey{
		return $this->key;
	}
}
