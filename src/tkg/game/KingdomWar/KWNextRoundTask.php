<?php

namespace tkg\game\KingdomWar;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\level\Level;

class KWNextRoundTask extends PluginTask {
	private $plugin;
	private $level;
	
	public function __construct(KWPlugIn $plugin, $level) {
		$this->plugin = $plugin;
		$this->level = $level;
		parent::__construct ( $plugin );
	}
	
	public function onRun($ticks) {
		$this->getPlugin()->kwManager->handleStartTheGame($this->level);
	}
	
	public function getPlugin() {
		return $this->plugin;
	}
	
	public function onCancel() {
	}
}
