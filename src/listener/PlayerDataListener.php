<?php
declare(strict_types=1);

namespace jasonwynn10\VeinMiner\listener;

use jasonwynn10\VeinMiner\data\PlayerPreferences;
use jasonwynn10\VeinMiner\VeinMiner;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

final class PlayerDataListener implements Listener{

	public function __construct(private VeinMiner $plugin) {}

	public function onPlayerJoin(PlayerJoinEvent $event) : void {
		$player = $event->getPlayer();
		$playerData = PlayerPreferences::get($player);

		// If the directory is only just created, there's no player data to read from anyways
		if(!@\mkdir($this->plugin->getPlayerDataDirectory())) {
			return;
		}

		$playerData->readFromFile($this->plugin->getPlayerDataDirectory());
	}

	public function onPlayerQuit(PlayerQuitEvent $event) : void {
		PlayerPreferences::get($event->getPlayer())->writeToFile($this->plugin->getPlayerDataDirectory());
	}

}