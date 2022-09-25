<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\economy;

use jasonwynn10\VeinMiner\data\AlgorithmConfig;
use jasonwynn10\VeinMiner\utils\VMConstants;
use jasonwynn10\VeinMiner\VeinMiner;
use pocketmine\player\Player;
use SOFe\Capital\Capital;
use SOFe\Capital\CapitalException;
use SOFe\Capital\LabelSet;
use SOFe\Capital\Schema\Complete;
use SOFe\InfoAPI\AnonInfo;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\PlayerInfo;

/**
 * An implementation of {@link EconomyModifier} to make use of a Capital-supported
 * economy plugin.
 *
 * @author Jason Wynn - jasonwynn10
 */
final class CapitalBasedEconomyModifier implements EconomyModifier{

	private CONST CAPITAL_VERSION = '0.1.2';

	private Complete $selector;

	public function __construct() {
		Capital::api(self::CAPITAL_VERSION, function(Capital $api) {
			$this->selector = $api->completeConfig(null); // use null to get the default schema from Capital
		});
	}

	/**
	 * @inheritDoc
	 */
	public function shouldCharge(Player $player, AlgorithmConfig $config) : bool{
		return $config->getCost() > 0.0 && !$player->hasPermission(VMConstants::PERMISSION_FREE_ECONOMY);
	}

	/**
	 * Capital docs say to get the current balance from InfoAPI, so we use that. There happens to be a benefit of it
	 * being in-sync, so we can use it to check if the player has enough money without having to handle any fail cases.
	 *
	 * @inheritDoc
	 */
	public function hasSufficientBalance(Player $player, AlgorithmConfig $config) : bool{
		$money = (int) InfoAPI::resolve('{money}', new class([
			"speaker" => new PlayerInfo($player),
		]) extends AnonInfo {});
		return $money >= $config->getCost();
	}

	/**
	 * @inheritDoc
	 */
	public function charge(Player $player, AlgorithmConfig $config) : void{
		Capital::api(self::CAPITAL_VERSION, function(Capital $api) use ($player, $config) {
			try {
				yield from $api->takeMoney(
					VeinMiner::getInstance()->getName(),
					$player,
					$this->selector,
					(int) $config->getCost() * 100, // round up to int
					new LabelSet(["reason" => "VeinMiner usage fee"]),
				);
			} catch(CapitalException $e) {
				$player->sendMessage("[VeinMiner] An error occurred while charging your account: " . $e->getMessage());
			}
		});
	}

	/**
	 * Check whether or not an economy implementation was found.
	 *
	 * @return true if economy is enabled, false otherwise
	 */
	public function hasEconomyPlugin() : bool{
		return \class_exists(Capital::class);
	}
}