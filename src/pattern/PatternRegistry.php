<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\pattern;

final class PatternRegistry{

	/** @var VeinMiningPattern[] $patterns */
	private array $patterns = [];

	/**
	 * Register a new VeinMiningPattern implementation.
	 *
	 * @param VeinMiningPattern $pattern the pattern to register
	 */
	public function registerPattern(VeinMiningPattern $pattern) : void {
		$this->patterns[(string)$pattern->getKey()] = $pattern;
	}

	/**
	 * Get the pattern associated with the given key.
	 *
	 * @param string $key the key of the pattern to retrieve
	 *
	 * @return VeinMiningPattern|null the pattern. null if no pattern matches the given key
	 */
	public function getPattern(string $key) : ?VeinMiningPattern{
		return $this->patterns[$key] ?? null;
	}

	/**
	 * Get the pattern associated with the given key or default if one is not registered.
	 *
	 * @param string $key the key of the pattern to retrieve
	 * @param VeinMiningPattern $defaultPattern the default pattern in the case the key is not registered
	 *
	 * @return VeinMiningPattern the pattern. The default pattern if no pattern matches the given key
	 */
	public function getPatternOrDefault(string $key, VeinMiningPattern $defaultPattern) : VeinMiningPattern{
		return $this->patterns[$key] ?? $defaultPattern;
	}

	/**
	 * Unregister the provided pattern from the pattern registry.
	 *
	 * @param string|VeinMiningPattern $pattern the pattern to unregister
	 */
	public function unregisterPattern(string|VeinMiningPattern $pattern) : void {
		unset($this->patterns[is_string($pattern) ? $pattern : $pattern->getKey()]);
	}

	/**
	 * Get an immutable set of all registered patterns.
	 *
	 * @return VeinMiningPattern[] all registered patterns
	 */
	public function getPatterns() : array{
		return $this->patterns;
	}

	/**
	 * Clear all patterns from the registry.
	 */
	public function clearPatterns() : void{
		$this->patterns = [];
	}

}