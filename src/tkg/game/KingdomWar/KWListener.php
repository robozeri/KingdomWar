<?php

namespace tkg\game\KingdomWar;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\level\Explosion;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\math\Vector2 as Vector2;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\block\Block;
use pocketmine\network\protocol\Info;
use pocketmine\network\protocol\LoginPacket;
use pocketmine\command\defaults\TeleportCommand;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;

class KWListener extends MiniGameBase implements Listener {
	public function __construct(KWPlugIn $plugin) {
		parent::__construct ( $plugin );
	}

    /**
     * @param BlockBreakEvent $event
     */
	public function onBlockBreak(BlockBreakEvent $event) {
		$player = $event->getPlayer ();
		$b = $event->getBlock ();
		if ($this->getPlugin ()->pos_display_flag == 1) {
			$event->getPlayer ()->sendMessage ( "BREAKED: [x=" . $b->x . " y=" . $b->y . " z=" . $b->z . "]" );
		}
		// @fix1- team can only break enermy flag and not own
		$redTeamFlagPos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG_RED_TEAM );
		if ((round ( $b->x ) == round ( $redTeamFlagPos->x ) && round ( $b->y ) == round ( $redTeamFlagPos->y ) && round ( $b->z ) == round ( $redTeamFlagPos->y ))) {
			if (isset ( $this->pgin->redTeamPlayers [$player->getName ()] )) {
				// update again to fix color issue
				$this->getBuilder ()->addBlueTeamFlag ( $player->getLevel (), 171, 14 );
				$event->setCancelled ( true );
			}
		}
		$blueTeamFlagPos = $this->getSetup ()->getFlagPos ( KWSetup::KW_FLAG_BLUE_TEAM );
		if ((round ( $b->x ) == round ( $blueTeamFlagPos->x ) && round ( $b->y ) == round ( $blueTeamFlagPos->y ) && round ( $b->z ) == round ( $blueTeamFlagPos->z ))) {
			if (isset ( $this->pgin->blueTeamPLayers [$player->getName ()] )) {
				// update again to fix color issue
				$this->getBuilder ()->addBlueTeamFlag ( $player->getLevel (), 171, 11 );
				$event->setCancelled ( true );
			}
		}		
		// @fix #2 stop player break anything else other than the flags
		if (strtolower ( $player->level->getName () ) == strtolower ( $this->getSetup ()->getKWWorldName () )) {
			if ($this->getSetup ()->isKWWorldBlockBreakDisable () || ! $player->isOp()) {
				if ($b->getId()!=171) {
					$event->setCancelled ( true );
				}
			}
		}
	}

    /**
     * @param BlockPlaceEvent $event
     */
	public function onBlockPlace(BlockPlaceEvent $event) {
		$player = $event->getPlayer ();
		$b = $event->getBlock ();
		if ($this->getPlugin ()->pos_display_flag == 1) {
			$player->sendMessage ( "PLACED:*" . $b->getName () . " [x=" . $b->x . " y=" . $b->y . " z=" . $b->z . "]" );
		}
		if ($this->getPlugin ()->gameMode == 1) {
			// check if the flag if the enermy one
			if (isset ( $this->getPlugin ()->blueTeamPLayers [$player->getName ()] )) {
				$this->getManager ()->checkBlueTeamCapturedEnermyFlag ( $player, $player->level, $b );
				return;
			}
			if (isset ( $this->getPlugin ()->redTeamPlayers [$player->getName ()] )) {
				$this->getManager ()->checkRedTeamCapturedEnermyFlag ( $player, $player->level, $b );
				return;
			}
		}
		// @fix #2 stop player place anything else other than the flags
		if (strtolower ( $player->level->getName () ) == strtolower ( $this->getSetup ()->getKWWorldName () )) {
			if ($this->getSetup ()->isKWWorldBlockPlaceDisable () || !$player->isOp()) {
				if ($b->getId()!=171) {
					$event->setCancelled ( true );
				}
			}
		}	
	}
	
	/**
	 * OnPlayerJoin
	 *
	 * @param PlayerJoinEvent $event        	
	 */
	public function onPlayerJoin(PlayerJoinEvent $event) {
		if ($event->getPlayer () instanceof Player) {
			$event->getPlayer ()->addAttachment ( $this->getPlugin (), "kw.plugin.ctf", true );
			if ($this->getManager () == null) {
				$this->log ( " getManager is null!" );
			} else {
				$this->getManager ()->handlePlayerEntry ( $event->getPlayer () );
			}
		}
	}

    /**
     * @param PlayerRespawnEvent $event
     */
	public function onPlayerRespawn(PlayerRespawnEvent $event) {
		if ($event->getPlayer () instanceof Player) {
			if ($this->getManager () == null) {
				$this->log ( " getManager is null!" );
			} else {
				$this->getManager ()->handlePlayerEntry ( $event->getPlayer () );
			}			
			$event->getPlayer()->getLevel()->getBlockLightAt($event->getPlayer()->x, $event->getPlayer()->y, $event->getPlayer()->z);
		}
	}
	
	/**
	 *
	 * @param PlayerQuitEvent $event        	
	 */
	public function onQuit(PlayerQuitEvent $event) {
		// @fix - remove captured flag
		if ($event->getPlayer () instanceof Player) {
			$this->getManager ()->handlePlayerQuit ( $event->getPlayer () );
		}
	}
	
	/**
	 * OnPlayerInteract
	 *
	 * @param PlayerInteractEvent $event        	
	 */
	public function onPlayerInteract(PlayerInteractEvent $event) {
		$blockTouched = $event->getBlock ();
		$player = $event->getPlayer ();
		$level = $event->getPlayer ()->getLevel ();
		$b = $event->getBlock ();
		if ($this->getPlugin ()->pos_display_flag == 1) {
			$event->getPlayer ()->sendMessage ( "TOUCHED: [x=" . $b->x . " y=" . $b->y . " z=" . $b->z . "]" );
		}
		// process clickable blocks
		$this->getManager ()->onClickStartGameButton ( $level, $player, $blockTouched );
		$this->getManager ()->onClickLeaveGameButton ( $level, $player, $blockTouched );
		$this->getManager ()->onClickStopGameButton ( $level, $player, $blockTouched );
		
		// process clickable signs
		$this->getManager ()->onClickJoinRedTeamSign ( $player, $blockTouched );
		$this->getManager ()->onClickJoinBlueTeamSign ( $player, $blockTouched );
		$this->getManager ()->onClickNewGameSign ( $player, $blockTouched );
		$this->getManager ()->onClickViewGameStatsSign ( $player, $blockTouched );
		
		// process sign setup actions
		if ($this->getPlugin ()->setupModeAction != "") {
			$this->getSetup ()->handleClickSignSetup ( $player, $this->getPlugin ()->setupModeAction, new Position ( $b->x, $b->y, $b->z ) );
			$this->getSetup ()->handleSetBlockSetup ( $player, $this->getPlugin ()->setupModeAction, $b->getId () );
		}
	}

    /**
     * @param PlayerDeathEvent $event
     */
	public function onPlayerDeath(EntityDeathEvent $event) {
		// player held the flag until death
		if ($event->getEntity () instanceof Player) {
			$this->getManager ()->handlePlayerQuit ( $event->getEntity () );
		}
	}

    /***
     * @param EntityDamageEvent $event
     */
    public function onPlayerHurt (EntityDamageEvent $event) {
        if ($event instanceof EntityDamageByEntityEvent) {
            if ($event->getEntity() instanceof Player && $event->getDamager() instanceof Player) {
                 if ( isset($this->getPlugin()->redTeamPlayers[$event->getEntity()->getName()]) && isset($this->getPlugin()->redTeamPlayers[$event->getDamager()->getName()])) {
                     $event->setCancelled(true);
                 } elseif( isset($this->getPlugin()->blueTeamPLayers[$event->getEntity()->getName()]) && isset($this->getPlugin()->blueTeamPLayers[$event->getDamager()->getName()])) {
                    $event->setCancelled(true);
                }
            }
        }

    }
