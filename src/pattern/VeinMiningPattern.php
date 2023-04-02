<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\pattern;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use jasonwynn10\VeinMiner\data\block\VeinBlock;
use jasonwynn10\VeinMiner\data\MaterialAlias;
use jasonwynn10\VeinMiner\tool\ToolCategory;
use jasonwynn10\VeinMiner\tool\ToolTemplate;
use jasonwynn10\VeinMiner\utils\NamespacedKey;
use pocketmine\block\Block;
use Ramsey\Collection\Set;

/**
 * Represents a mining algorithm capable of allocating which blocks should be broken by VeinMiner
 * when a successful vein mine occurs. It is recommended that implementations of VeinMiningPattern
 * are singleton instances, although this is not a requirement.
 */
interface VeinMiningPattern{

	/**
	 * Allocate the blocks that should be broken by the vein mining pattern. Note that the breaking
	 * of the blocks should not be handled by the pattern, but rather the plugin itself. This method
	 * serves primarily to search for valid blocks to break in a vein.
	 * <p>
	 * <b>NOTE:</b> If null is added to the "blocks" set, a NullPointerException will be thrown and
	 * the method will fail.
	 *
	 * @param Set<Block>         $blocks          a set of all blocks to break. Valid blocks should be added here. The "origin"
	 *                                            block passed to this method will be added automatically
	 * @param VeinBlock          $type            the type of VeinBlock being vein mined
	 * @param Block              $origin          the block where the vein mine was initiated
	 * @param ToolCategory       $category        the tool category used to break the block
	 * @param ToolTemplate|null  $template        the tool template used to break the block. May be null
	 * @param AlgorithmConfig    $algorithmConfig the algorithm configuration
	 * @param MaterialAlias|null $alias           an alias of the block being broken if one exists. May be null
	 */
	public function allocateBlocks(Set $blocks, VeinBlock $type, Block $origin, ToolCategory $category, ?ToolTemplate $template, AlgorithmConfig $algorithmConfig, ?MaterialAlias $alias = null) : void;

	public function getKey() : NamespacedKey;

}
