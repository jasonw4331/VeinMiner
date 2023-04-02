<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\data\block;

use jasonwynn10\VeinMiner\VeinMiner;
use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;
use pocketmine\item\ItemBlock;
use pocketmine\item\StringToItemParser;
use function preg_match_all;
use function strcmp;

/**
 * Represents a block that may be vein mined. These blocks may or may not contain
 * additional data or wildcards, therefore the result of {@link #encapsulates(Material)}
 * (and its overrides) may vary based on the implementation and whether or not the block
 * has additional data (see {@link #hasSpecificData()}).
 *
 * @author Parker Hawke - 2008Choco
 */
abstract class VeinBlock{

	/**
	 * Get the Bukkit {@link Material} represented by this block
	 *
	 * @return BlockIdentifier the material type
	 */
	abstract public function getType() : BlockIdentifier;

	/**
	 * Check whether or not this block includes more specific block data (for example,
	 * "minecraft:chest" would return false whereas "minecraft:chest[facing=north]"
	 * would return true due to the specified "facing" block state.
	 *
	 * @return true if specific data is defined, false if wildcarded to type only
	 */
	abstract public function hasSpecificData() : bool;

	/**
	 * Get the Bukkit {@link BlockData} represented by this block. If this VeinBlock
	 * has no specific data, this method will return the equivalent of
	 * {@link Material#createBlockData()} with no additional block state data.
	 *
	 * @return BlockIdentifier the block data
	 */
	abstract public function getBlockData() : BlockIdentifier;

	/**
	 * Check whether or not the provided block is encapsulated by this VeinBlock. If
	 * encapsulated, the provided block may be considered valid to vein mine.
	 * <p>
	 * The result of this method may vary based on whether or not this block has specific
	 * data. If specific data is defined, any non-specified states in the underlying
	 * BlockData will be ignored... only specified states will be compared. If otherwise,
	 * only the block's type will be compared.
	 *
	 * @param Block|BlockIdentifier $block the block to check
	 *
	 * @return true if encapsulated and valid to vein mine for this type, false otherwise
	 *
	 * @see #encapsulates(BlockData)
	 * @see #encapsulates(Material)
	 */
	abstract public function encapsulates(Block|BlockIdentifier $block) : bool;

	/**
	 * Get this VeinBlock instance as a readable data String. Similar to how
	 * {@link BlockData#getAsString()} returns a human-readable representation of block data,
	 * this will return a human-readable representation of the vein block based on its defined
	 * data (if any). It will be under a similar format as the aforementioned method.
	 *
	 * @return string the human-readable data string
	 */
	abstract public function asDataString() : string;

	/**
	 * Get whether or not this block is a wildcard.
	 *
	 * @return true if wildcard, false otherwise
	 */
	public function isWildcard() : bool{
		return false;
	}

	public function compareTo(VeinBlock $other) : int{
		return strcmp($this->asDataString(), $other->asDataString());
	}

	/**
	 * Get a VeinBlock based on block data with a set of states.
	 *
	 * @param Block|BlockIdentifier $data the block data for which to get a VeinBlock instance
	 *
	 * @return VeinBlock the VeinBlock instance
	 */
	public static function get(Block|BlockIdentifier $data) : VeinBlock{
		return BlockCache::MATERIAL()->getOrCache($data, new VeinBlockDatable($data));
	}

	/**
	 * Get a VeinBlock based on a String representation of its material and/or state.
	 * If the format of the String is inconsistent with how Minecraft formats its states,
	 * or if the type / (one or more of the) states are invalid or unknown, this method
	 * will return null. An example of valid formats are as follows:
	 * <pre>{@code
	 * chest
	 * minecraft:chest
	 * minecraft:chest[waterlogged=true]
	 * minecraft:chest[facing=north,waterlogged=true]
	 * *
	 * }</pre>
	 *
	 * @param string $value the value from which to get a VeinBlock instance.
	 *
	 * @return VeinBlock|null the parsed VeinBlock instance. null if malformed
	 */
	public static function fromString(string $value) : ?VeinBlock{
		if($value === '*')
			return static::wildcard();

		if(preg_match_all(VeinMiner::$BLOCK_DATA_PATTERN, $value, $matches) === 0){
			return null;
		}

		//$specificData = isset($matches[1]);

		$data = StringToItemParser::getInstance()->parse($value);

		if(!$data instanceof ItemBlock){
			return null;
		}

		return VeinBlock::get($data->getBlock());
	}

	/**
	 * Get the wildcard {@link VeinBlock} instance.
	 *
	 * @return VeinBlock the wildcard instance
	 */
	public static function wildcard() : VeinBlock{
		return VeinBlockWildcard::getInstance();
	}

	/**
	 * Clear the VeinBlock cache. This may slightly decrease performance until the cache returns
	 * to a more populated state.
	 */
	public static function clearCache() : void{
		BlockCache::clear();
	}
}
