<?php
declare(strict_types=1);

namespace jasonw4331\VeinMiner\economy;

use jasonw4331\VeinMiner\data\AlgorithmConfig;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

/**
 * An implementation of {@link EconomyModifier} with no effect on the player.
 * Players will never require money to be withdrawn.
 *
 * @author Parker Hawke - 2008Choco
 */
final class EmptyEconomyModifier implements EconomyModifier{
	use SingletonTrait;

	private function __construct(){ }

	/**
	 * @inheritDoc
	 */
	public function shouldCharge(Player $player, AlgorithmConfig $config) : bool{
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function hasSufficientBalance(Player $player, AlgorithmConfig $config) : bool{
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function charge(Player $player, AlgorithmConfig $config) : void{ }
}
