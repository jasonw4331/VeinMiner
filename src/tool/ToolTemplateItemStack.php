<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\tool;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use pocketmine\item\Item;
use function array_diff;
use function count;

final class ToolTemplateItemStack extends ToolTemplate{
	private AlgorithmConfig $config;

	/**
	 * Construct a new ToolTemplate with a specific type, name and lore.
	 *
	 * @param ToolCategory         $category the category to which this template belongs
	 * @param Item                 $type     the type for which to check
	 * @param AlgorithmConfig|null $config   the algorithm configuration for this template
	 * @param string|null          $name     the name for which to check (null if none)
	 * @param array|null           $lore     the lore for which to check (null if none)
	 */
	public function __construct(private ToolCategory $category, private Item $type, ?AlgorithmConfig $config = null, private ?string $name = null, private ?array $lore = null){
		$this->config = $config ?? clone $category->getConfig();
	}

	/**
	 * Get the specific type defined by this template. If {@link Material#AIR}, no specific type is
	 * defined.
	 *
	 * @return Item the specific type
	 */
	public function getType() : Item{
		return $this->type;
	}

	/**
	 * Get the item name defined by this template. Chat colours may or may not be translated in the
	 * result of this value depending on the implementation. No standard is defined.
	 *
	 * @return string|null the name of the template. null if none
	 */
	public function getName() : ?string{
		return $this->name;
	}

	/**
	 * Get the item lore defined by this template. Chat colours may or may not be translated in the
	 * result of this value depending on the implementation. No standard is defined.
	 *
	 * @return array|null the lore of the template. null if none
	 */
	public function getLore() : ?array{
		return $this->lore;
	}

	public function matches(Item $item) : bool{
		if(!$item->equals($this->type, false)){
			return false;
		}

		if($this->name != null && ($this->name != $item->getName())){
			return false;
		}

		return $this->lore === null || count(array_diff($this->lore, $item->getLore())) === 0;
	}

	public function getConfig() : ?AlgorithmConfig{
		return $this->config;
	}

	public function getCategory() : ToolCategory{
		return $this->category;
	}

	public function __toString() : string{
		return $this->getType()->__toString();
	}
}
