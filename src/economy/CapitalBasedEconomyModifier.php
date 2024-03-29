<?php
declare(strict_types=1);

namespace jasonw4331\VeinMiner\economy;

use jasonw4331\VeinMiner\data\AlgorithmConfig;
use jasonw4331\VeinMiner\utils\VMConstants;
use jasonw4331\VeinMiner\VeinMiner;
use pocketmine\player\Player;
use SOFe\Capital\Capital;
use SOFe\Capital\CapitalException;
use SOFe\Capital\LabelSet;
use SOFe\Capital\Schema\Complete;
use SOFe\InfoAPI\AnonInfo;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\PlayerInfo;
use function class_exists;

/**
 * An implementation of {@link EconomyModifier} to make use of a Capital-supported
 * economy plugin.
 *
 * @author Jason Wynn - jasonw4331
 */
final class CapitalBasedEconomyModifier implements EconomyModifier{

	private const CAPITAL_VERSION = '0.1.2';

	private Complete $selector;

	public function __construct(){
		Capital::api(self::CAPITAL_VERSION, function(Capital $api){
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
		]) extends AnonInfo{
		});
		return $money >= $config->getCost();
	}

	/**
	 * @inheritDoc
	 */
	public function charge(Player $player, AlgorithmConfig $config) : void{
		Capital::api(self::CAPITAL_VERSION, function(Capital $api) use ($player, $config){
			try{
				yield from $api->takeMoney(
					VeinMiner::getInstance()->getName(),
					$player,
					$this->selector,
					(int) $config->getCost() * 100, // round up to int
					new LabelSet(["reason" => "VeinMiner usage fee"]),
				);
			}catch(CapitalException $e){
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
		return class_exists(Capital::class);
	}
}
