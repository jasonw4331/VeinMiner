<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\pattern;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use jasonwynn10\VeinMiner\data\MaterialAlias;
use jasonwynn10\VeinMiner\PatternUtils;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use jasonwynn10\VeinMiner\tool\ToolTemplate;
use jasonwynn10\VeinMiner\utils\NamespacedKey;
use jasonwynn10\VeinMiner\VeinMiner;
use jasonwynn10\VeinMiner\VeinMiningPattern;
use pocketmine\block\Block;
use pocketmine\utils\SingletonTrait;

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
	use SingletonTrait{
		reset as private reset; // don't let someone delete our instance
		setInstance as private setInstance; // don't let someone set our instance
	}

	/** @var Block[] $blockBuffer */
	private array $blockBuffer = [];
	private NamespacedKey $key;

	private function __construct(){
		$this->key = new NamespacedKey(VeinMiner::getInstance(), 'thorough');
	}

	public function allocateBlocks(array &$blocks, VeinBlock $type, Block $origin, ToolCategory $category, ?ToolTemplate $template, AlgorithmConfig $algorithmConfig, ?MaterialAlias $alias = null) : void{
		$maxVeinSize = $algorithmConfig->getMaxVeinSize();
		$facesToMine = PatternUtils::getFacesToMine($algorithmConfig);

		while(count($blocks) < $maxVeinSize){
			$trackedBlocks = \SplFixedArray::fromArray($blocks, false);
			while($trackedBlocks->valid() && count($blocks) + count($this->blockBuffer) < $maxVeinSize){
				$trackedBlocks->next();
				$current = $trackedBlocks->current();
				foreach($facesToMine as $face){
					if(count($blocks) + count($this->blockBuffer) >= $maxVeinSize){
						break;
					}
					$nextBlock = $face->getRelative($current);
					if(in_array($nextBlock, $blocks) || !PatternUtils::isOfType($type, $origin, $alias, $nextBlock)){
						continue;
					}

					$this->blockBuffer[] = $nextBlock;
				}
			}

			if(count($this->blockBuffer) === 0) {
				break;
			}

			array_push($blocks, ...$this->blockBuffer);
			$this->blockBuffer = [];
		}
	}

	public function getKey() : NamespacedKey{
		return $this->key;
	}
}