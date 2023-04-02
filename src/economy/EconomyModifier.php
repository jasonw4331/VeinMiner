<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\economy;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use pocketmine\player\Player;

interface EconomyModifier{

	/**
	 * Check whether the provided player should have money withdrawn from their
	 * account before vein mining.
	 *
	 * @param Player          $player the player to check
	 * @param AlgorithmConfig $config the relevant algorithm config (if necessary)
	 *
	 * @return true if money should be withdrawn, false otherwise
	 */
	public function shouldCharge(Player $player, AlgorithmConfig $config) : bool;

	/**
	 * Check whether or not the provided player has a sufficient amount of money
	 * to be charged.
	 *
	 * @param Player          $player the player to check
	 * @param AlgorithmConfig $config the relevant algorithm config (if necessary)
	 *
	 * @return true if the player has a sufficient amount of money, false otherwise
	 */
	public function hasSufficientBalance(Player $player, AlgorithmConfig $config) : bool;

	/**
	 * Charge the specified player.
	 *
	 * @param Player          $player the player to check
	 * @param AlgorithmConfig $config the relevant algorithm config (if necessary)
	 */
	public function charge(Player $player, AlgorithmConfig $config) : void;

}
