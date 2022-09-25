<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\data\block;

use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;
use pocketmine\utils\EnumTrait;
use Ramsey\Collection\Map\AssociativeArrayMap;

/**
 * @generate-registry-docblock
 */
final class BlockCache{
	use EnumTrait{
		__construct as Enum___construct;
	}

	/** @var AssociativeArrayMap<VeinBlock> $cached */
	private AssociativeArrayMap $cached;

	/**
	 * @inheritDoc
	 */
	protected static function setup() : void{
		self::register(new self('MATERIAL'));
		self::register(new self('BLOCK_DATA'));
	}

	private function __construct(string $enumName) {
		$this->Enum___construct($enumName);
		$this->cached = new AssociativeArrayMap();
	}

	public function getOrCache(Block|BlockIdentifier $type, VeinBlock $defaultSupplier) : VeinBlock {
		if($type instanceof Block)
			$type = $type->getIdInfo();
		return $this->cached->putIfAbsent(\spl_object_hash($type), $defaultSupplier) ?? $defaultSupplier;
	}

	static function clear() : void {
		foreach(self::getAll() as $blockCache) {
			$blockCache->cached->clear();
		}
	}
}