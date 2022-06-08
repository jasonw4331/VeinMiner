<?php
declare(strict_types=1);
namespace jasonwynn10\VeinMiner\api;

use jasonwynn10\VeinMiner\utils\VMConstants;
use jasonwynn10\VeinMiner\VeinMiner;
use pocketmine\player\Player;
use pocketmine\utils\EnumTrait;

/**
 * @generate-registry-docblock
 */
final class ActivationStrategy{
	use EnumTrait {
		__construct as Enum___construct;
	}

	/** @var callable $callable */
	private $callable;

	protected static function setup() : void {
		self::registerAll(
			new self('NONE', static fn($_) => false),
			new self('SNEAK', static fn(Player $player) => $player->isSneaking()),
			new self('STAND', static fn(Player $player) => !$player->isSneaking()),
			new self('ALWAYS', static fn($_) => true),
		);
	}

	/**
	 * @param string   $name
	 * @param callable $callable
	 * @phpstan-param callable(Player): bool $callable
	 */
	private function __construct(
		string $name,
		callable $callable
	){
		$this->Enum___construct($name);
		$this->callable = $callable;
	}

	/**
	 * Check whether a Player is capable of vein mining according to this activation.
	 *
	 * @param Player $player the player to check
	 *
	 * @return true if valid to vein mine, false otherwise
	 */
	public function isValid(Player $player) : bool {
		return ($this->callable)($player);
	}

	/**
	 * Get the default {@link ActivationStrategy} as specific in the config.
	 *
	 * @return self the default activation strategy
	 */
	public static function getDefaultActivationStrategy() : self {
		$plugin = VeinMiner::getInstance();

		$strategyName = $plugin->getConfig()->get(VMConstants::CONFIG_DEFAULT_ACTIVATION_STRATEGY, null);
		if ($strategyName === null) {
			return self::SNEAK();
		}

		try{
			$strategy = self::__callStatic($strategyName, []);
		}catch(\Error $e) {
			$strategy = self::SNEAK();
		}
		return $strategy;
	}
}