<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\data\block;

use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;

class VeinBlockDatable extends VeinBlock{

	private Block $data;

	public function __construct(Block $data) {
		$this->data = clone $data;
	}

	/**
	 * @inheritDoc
	 */
	public function getType() : BlockIdentifier{
		return $this->data->getIdInfo();
	}

	/**
	 * @inheritDoc
	 */
	public function hasSpecificData() : bool{
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getBlockData() : BlockIdentifier{
		return $this->data->getIdInfo();
	}

	/**
	 * @inheritDoc
	 */
	public function encapsulates(BlockIdentifier|Block $block) : bool{
		if($block instanceof BlockIdentifier)
			return $block === $this->data->getIdInfo();
		return $block->getIdInfo() === $this->data->getIdInfo();
	}

	/**
	 * @inheritDoc
	 */
	public function asDataString() : string{
		return $this->data->__toString();
	}

	public function equals(Object $obj) : bool{
        if ($obj === $this) {
            return true;
        }
		if (!($obj instanceof VeinBlockDatable)) {
			return false;
		}

		return $this->data->getIdInfo() === $obj->getBlockData();
    }

    public function toString() : string {
		$dataString = $this->asDataString();
        return "{VeinBlockDatable:{\"Type\":\"" . $this->data->getName() . "\",\"Data\":\"" . mb_substr($dataString, mb_strpos($dataString, '[')) . "\"}}";
    }
}