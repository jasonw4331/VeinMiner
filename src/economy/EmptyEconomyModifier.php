<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\economy;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use pocketmine\player\Player;

/**
 * An implementation of {@link EconomyModifier} with no effect on the player.
 * Players will never require money to be withdrawn.
 *
 * @author Parker Hawke - 2008Choco
 */
final class EmptyEconomyModifier implements EconomyModifier{

	private static EmptyEconomyModifier $instance;

	private function __construct(){}

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
	public function charge(Player $player, AlgorithmConfig $config) : bool{
		return true;
	}

	/**
	 * Get an instance of the empty economy modifier.
	 *
	 * @return EmptyEconomyModifier this instance
	 */
	public static function get() : EmptyEconomyModifier{
		return self::$instance ??= new EmptyEconomyModifier();
	}
}