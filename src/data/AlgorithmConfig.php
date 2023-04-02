<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\data;

use jasonwynn10\VeinMiner\utils\VMConstants;
use pocketmine\Server;
use pocketmine\world\World;
use function array_diff;
use function count;
use function ctype_digit;
use function in_array;
use function is_array;
use function is_bool;
use function is_int;
use function is_string;
use function max;

final class AlgorithmConfig{
	private bool $repairFriendly = false;
	private bool $includeEdges = false;
	private int $maxVeinSize = 64;
	private float $cost = 0.0;
	private array $disabledWorlds = [];

	public function __construct(array $provided = [], ?AlgorithmConfig $defaultValues = null){

		$this->repairFriendly = ($provided[VMConstants::CONFIG_REPAIR_FRIENDLY_VEINMINER] ?? $defaultValues?->repairFriendly) ?? $this->repairFriendly;
		$this->includeEdges = ($provided[VMConstants::CONFIG_INCLUDE_EDGES] ?? $defaultValues?->includeEdges) ?? $this->includeEdges;
		$this->maxVeinSize = ($provided[VMConstants::CONFIG_MAX_VEIN_SIZE] ?? $defaultValues?->maxVeinSize) ?? $this->maxVeinSize;
		$this->cost = ($provided[VMConstants::CONFIG_COST] ?? $defaultValues?->cost) ?? $this->cost;

		$disabledWorlds = $provided[VMConstants::CONFIG_DISABLED_WORLDS] ?? [];
		if(count($disabledWorlds) < 1 && $defaultValues !== null){
			$this->disabledWorlds = $defaultValues->disabledWorlds;
		}

		foreach($disabledWorlds as $worldName){
			$world = Server::getInstance()->getWorldManager()->getWorldByName($worldName);
			if($world === null){
				continue;
			}
			$this->disabledWorlds[] = $world->getId();
		}

	}

	/**
	 * Set whether or not vein miner should be repair-friendly. If true, vein miner will
	 * cease execution before the tool has lost all its durability allowing the user to
	 * repair their tool before it gets broken. If false, vein miner will cease execution
	 * after the tool has broken.
	 *
	 * @param bool $repairFriendly the repair-friendly state
	 *
	 * @return AlgorithmConfig this instance. Allows for chained method calls
	 */
	public function repairFriendly(bool $repairFriendly) : AlgorithmConfig{
		$this->repairFriendly = $repairFriendly;
		return $this;
	}

	/**
	 * Get whether or not vein miner should be repair-friendly. See
	 * {@link #repairFriendly(boolean)}.
	 *
	 * @return true if repair-friendly, false otherwise
	 */
	public function isRepairFriendly() : bool{
		return $this->repairFriendly;
	}

	/**
	 * Set whether or not vein miner should search for vein mineable blocks along the
	 * farthest edges of a block (i.e. north north east, north north west, etc.).
	 *
	 * @param bool $includeEdges whether to include edges
	 *
	 * @return AlgorithmConfig this instance. Allows for chained method calls
	 */
	public function includeEdges(bool $includeEdges) : AlgorithmConfig{
		$this->includeEdges = $includeEdges;
		return $this;
	}

	/**
	 * Check whether or not vein miner should include edges. See {@link #includesEdges()}.
	 *
	 * @return true if includes edges, false otherwise
	 */
	public function includesEdges() : bool{
		return $this->includeEdges;
	}

	/**
	 * Set the maximum vein size.
	 *
	 * @param int $maxVeinSize the maximum vein size
	 *
	 * @return AlgorithmConfig this instance. Allows for chained method calls
	 */
	public function maxVeinSize(int $maxVeinSize) : AlgorithmConfig{
		if($maxVeinSize < 1){
			throw new \InvalidArgumentException("Max vein size must be greater than 0");
		}

		$this->maxVeinSize = $maxVeinSize;
		return $this;
	}

	/**
	 * Get the maximum vein size.
	 *
	 * @return int the maximum vein size
	 */
	public function getMaxVeinSize() : int{
		return $this->maxVeinSize;
	}

	/**
	 * Set the amount of money required to vein mine. Note, this feature requires Vault
	 * and an economy plugin, else it is ignored.
	 *
	 * @param float $cost the cost
	 *
	 * @return AlgorithmConfig this instance. Allows for chained method calls
	 */
	public function cost(float $cost) : AlgorithmConfig{
		$this->cost = $cost;
		return $this;
	}

	/**
	 * Get the economic cost.
	 *
	 * @return float the cost
	 */
	public function getCost() : float{
		return $this->cost;
	}

	/**
	 * Add a world in which vein miner should be disabled.
	 *
	 * @param World $world the world in which to disable vein miner
	 *
	 * @return AlgorithmConfig this instance. Allows for chained method calls
	 */
	public function disabledWorld(World $world) : AlgorithmConfig{
		$this->disabledWorlds[] = $world->getId();
		return $this;
	}

	/**
	 * Remove a world in which vein miner is disabled. Vein miner will be usable again.
	 *
	 * @param World $world the world in which to enabled vein miner
	 *
	 * @return AlgorithmConfig this instance. Allows for chained method calls
	 */
	public function unDisabledWorld(World $world) : AlgorithmConfig{
		$this->disabledWorlds = array_diff($this->disabledWorlds, [$world->getId()]);
		return $this;
	}

	/**
	 * Check whether or not vein miner is disabled in the specified world.
	 *
	 * @param world the world to check
	 *
	 * @return true if disabled, false otherwise
	 */
	public function isDisabledWorld(World $world) : bool{
		return in_array($world->getId(), $this->disabledWorlds, true);
	}

	/**
	 * Read configured values from a raw {@literal Map<String, Object>}. This is not a
	 * recommended means of reading data and exists solely for internal use.
	 *
	 * @param array $raw the raw data from which to read configured values
	 *
	 * @deprecated not set for removal but AVOID AT ALL COSTS. Constructors and builder
	 * methods should be the preferred approach to reading configured values.
	 */
	public function readUnsafe(array $raw) : void{
		$repairFriendlyVeinMiner = $raw[VMConstants::CONFIG_REPAIR_FRIENDLY_VEINMINER];
		$includeEdges = $raw[VMConstants::CONFIG_INCLUDE_EDGES];
		$maxVeinSize = $raw[VMConstants::CONFIG_MAX_VEIN_SIZE];
		$cost = $raw[VMConstants::CONFIG_COST];
		$disabledWorlds = $raw[VMConstants::CONFIG_DISABLED_WORLDS];

		if(is_bool($repairFriendlyVeinMiner)){
			$this->repairFriendly($repairFriendlyVeinMiner);
		}
		if(is_bool($includeEdges)){
			$this->includeEdges($includeEdges);
		}
		if(is_int($maxVeinSize)){
			$this->maxVeinSize(max($maxVeinSize, 1));
		}
		if(ctype_digit($cost)){
			$this->cost(max($cost, 0.0));
		}
		if(is_array($disabledWorlds)){
			foreach($disabledWorlds as $world){
				if(is_string($world)){
					$world = Server::getInstance()->getWorldManager()->getWorldByName($world);
				}
				if($world instanceof World){
					$this->disabledWorld($world);
				}
			}
		}
	}

	public function equals(object $obj) : bool{
		return $obj === $this || ($obj instanceof AlgorithmConfig && $obj->isRepairFriendly() === $this->repairFriendly && $obj->includeEdges === $this->includeEdges && $obj->getMaxVeinSize() === $this->maxVeinSize && $obj->getCost() === $this->cost && count(array_diff($this->disabledWorlds, $obj->disabledWorlds)) === 0);
	}

}
