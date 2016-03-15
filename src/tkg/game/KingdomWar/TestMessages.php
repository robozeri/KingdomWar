<?php

namespace tkg\game\KingdomWar;

use pocketmine\utils\TextFormat;

class TestMessages extends MiniGameBase {

	public function __construct(KWPlugIn $plugin) {
		parent::__construct ( $plugin );
		$this->ctfmsg = new KWMessages($plugin);
	}
	
	public function runTests() {
		$this->testMessage("kw.name");
		$this->testMessage("kw.status");		
		$this->testMessage("team.scores.score");		
		$this->testMessage("kw.error.no-permission");
		$this->testMessage("kw.error.not-game-stop");
		$this->testMessage("kw.setup.success");
		$this->testMessage("kw.setup.failed");
		$this->testMessage("kw.setup.select");
		$this->testMessage("kw.setup.action");				
		$this->testMessage("arena.created");
		$this->testMessage("block.display-on");
		$this->testMessage("block.display-off");
		$this->testMessage("team.join-blue" );
		$this->testMessage("team.join-red");
		$this->testMessage("game.player-left" );
		$this->testMessage("game.player-stop" );		
		$this->testMessage("game.player-start");
		$this->testMessage("game.stats");
		$this->testMessage("team.scores.score");
		$this->testMessage("team.scores.red-players");		
		$this->testMessage("team.scores.players");
		$this->testMessage("team.scores.round");
		$this->testMessage("team.scores.blue-players");		
		$this->testMessage("team.scores.redteam-wins");
		$this->testMessage("team.scores.blueteam-wins");
		$this->testMessage("game.in-progress");
		$this->testMessage("game.new-game");		
		$this->testMessage("kw.error.blueteam-flag-exist" );
		$this->testMessage("kw.conglatulations");
		$this->testMessage("kw.red-team.capturedflag");
		$this->testMessage("kw.blue-team.score");
		$this->testMessage("kw.red-team.score");
		$this->testMessage("kw.error.redteam-flag-exist" );		
		$this->testMessage("kw.conglatulations");
		$this->testMessage("kw.blue-team.capturedflag");
		$this->testMessage("kw.blue-team.score" );
		$this->testMessage("kw.red-team.score" );		
		$this->testMessage("game.getready");		
		$this->testMessage("game.nextround");	
		$this->testMessage("game.roundstart");
		$this->testMessage("kw.finished");
		$this->testMessage("game.ticks");		
		$this->testMessage("kw.finished");
		$this->testMessage("team.welcome-blue");		
		$this->testMessage("team.tap-start");
		$this->testMessage("team.blue");
		$this->testMessage("team.joined-blue");
		$this->testMessage("team.members");	
		$this->testMessage("team.welcome-red");		
		$this->testMessage("team.tap-start");		
		$this->testMessage("team.red" );
		$this->testMessage("team.joined-red");
		$this->testMessage("team.members");		
		$this->testMessage("game.remove-equipment");
		$this->testMessage("kw.left-game");		
		$this->testMessage( "game.stop");
		$this->testMessage("kw.return-waiting-area");
		$this->testMessage("team.scores.red-players" );
		$this->testMessage("team.scores.players");		
		$this->testMessage("game.full" );
		$this->testMessage("team.scores.blue-players" );
		$this->testMessage("team.scores.players" );		
		$this->testMessage("game.resetting");
		$this->testMessage("kw.spawn_player");		
		$this->testMessage("sign.world-not-found");
		$this->testMessage("sign.teleport.spawn");
		$this->testMessage("sign.teleport.ctf");
		$this->testMessage("kw.error.wrong-sender");		
		$this->testMessage("game.not-enought-players");
		$this->testMessage("game.in-progress");		
		$this->testMessage("game.hit-stop" );
		$this->testMessage("game.round");
		$this->testMessage("game.go");
		$this->testMessage("kw.return-flag");
		$this->testMessage("team.left-blue");
		$this->testMessage("kw.return-flag");
		$this->testMessage("game.final.draw");
		$this->testMessage("game.final.red-win");		
		$this->testMessage("game.final.blue-win");				
		$this->testMessage("sign.world-not-found");
		$this->testMessage("sign.teleport.world");		
		$this->testMessage("sign.teleport.game");		
		$this->testMessage("sign.done" );
		$this->testMessage("game.start-equipment");
	}
	
	public function testMessage($key) {
		$value = $this->getMsg($key);
		if ($value==null) {
			$value = TextFormat::RED ."* KEY NOT FOUND !!!";
		}
		if ($key==$value) {
			$value = TextFormat::RED ."* KEY NOT FOUND !!!";
		}
		$this->getPlugIn()->getLogger()->info($key." = ".$value);
	}
}