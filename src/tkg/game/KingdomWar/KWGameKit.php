<?php

namespace tkg\game\KingdomWar;

use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;

class KWGameKit extends MiniGameBase {

	const DIR_KITS = "kits/";
	const KIT_BLUE_TEAM = "BlueTeam";
	const KIT_RED_TEAM = "RedTeam";	
	const KIT_UNKNOWN = "Unknown";
	
	public function __construct(CTFPlugIn $plugin) {
		parent::__construct ( $plugin );
		$this->init();
	}
	
	private function init() {
		@mkdir ( $this->plugin->getDataFolder () . self::DIR_KITS, 0777, true );
		$this->getKit(self::KIT_BLUE_TEAM);
		$this->getKit(self::KIT_RED_TEAM);		
	}

	
	public function putOnGameKit(Player $p, $kitType) {
		switch ($kitType) {
			case self::KIT_BLUE_TEAM :
				$this->loadKit(self::KIT_BLUE_TEAM, $p);
				break;
			case self::KIT_RED_TEAM :
			    $this->loadKit(self::KIT_RED_TEAM, $p);
				break;
			default :
			   // no armors kit
			   $this->loadKit(self::KIT_UNKNOWN, $p);
		}
	}
	
	public function getKit($kitName) {
		if (! (file_exists ( $this->getPlugin ()->getDataFolder () . self::DIR_KITS . strtolower ( $kitName ) . ".yml" ))) {
			
			if ($kitName == self::KIT_BLUE_TEAM) {
				
				return new Config ( $this->plugin->getDataFolder () . self::DIR_KITS . strtolower ( self::KIT_BLUE_TEAM ) . ".yml", Config::YAML, array (
						"kitName" => self::KIT_BLUE_TEAM,
						"isDefault" => false,
						"cost" => 0,
						"health" => 20,
						"armors" => array (
								"helmet" => array (
										"302",
										"0",
										"1" 
								),
								"chestplate" => array (
										"303",
										"0",
										"1" 
								),
								"leggings" => array (
										"304",
										"0",
										"1" 
								),
								"boots" => array (
										"305",
										"0",
										"1" 
								) 
						),
						"weapons" => array (
								"272" => array (
										"272",
										"0",
										"1" 
								),
								"50" => array (
										"50",
										"0",
										"1" 
								),
								"261" => array (
										"261",
										"0",
										"1" 
								),
								"262" => array (
										"262",
										"0",
										"64" 
								) 
						),
						"foods" => array (
								"260" => array (
										"260",
										"0",
										"2" 
								),
								"366" => array (
										"366",
										"0",
										"2" 
								),
								"320" => array (
										"320",
										"0",
										"2" 
								),
								"323" => array (
										"323",
										"0",
										"2" 
								),
								"364" => array (
										"364",
										"0",
										"2" 
								) 
						) 
				) );
			} elseif ($kitName == self::KIT_RED_TEAM) {
				return new Config ( $this->plugin->getDataFolder () . self::DIR_KITS . strtolower ( $kitName ) . ".yml", Config::YAML, array (
						"kitName" => self::KIT_RED_TEAM,
						"isDefault" => false,
						"cost" => 0,
						"health" => 20,
						"armors" => array (
								"helmet" => array (
										"306",
										"0",
										"1" 
								),
								"chestplate" => array (
										"307",
										"0",
										"1" 
								),
								"leggings" => array (
										"308",
										"0",
										"1" 
								),
								"boots" => array (
										"309",
										"0",
										"1" 
								) 
						),
						"weapons" => array (
								"272" => array (
										"272",
										"0",
										"1" 
								),
								"50" => array (
										"50",
										"0",
										"1" 
								),
								"261" => array (
										"261",
										"0",
										"1" 
								),
								"262" => array (
										"262",
										"0",
										"64" 
								) 
						),
						"foods" => array (
								"260" => array (
										"260",
										"0",
										"2" 
								),
								"366" => array (
										"366",
										"0",
										"2" 
								),
								"320" => array (
										"320",
										"0",
										"2" 
								),
								"323" => array (
										"323",
										"0",
										"2" 
								),
								"364" => array (
										"364",
										"0",
										"2" 
								) 
						) 
				) );
			} else {
				return new Config ( $this->plugin->getDataFolder () . self::DIR_KITS . strtolower ( $kitName ) . ".yml", Config::YAML, array (
						"kitName" => self::KIT_UNKNOWN,
						"isDefault" => false,
						"cost" => 0,
						"health" => 20,
						"armors" => array (
								"helmet" => array (
										"0",
										"0",
										"1" 
								),
								"chestplate" => array (
										"0",
										"0",
										"1" 
								),
								"leggings" => array (
										"0",
										"0",