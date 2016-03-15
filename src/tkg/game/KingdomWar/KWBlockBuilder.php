namespace tkg\game\KingdomWar;


use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;


class KWBlockBuilder extends MiniGameBase  {
	public $boardsize = 16;
	public $wallBlocksTypes = [ ];
	public $floorBlocksTypes = [ ];
	public $blueTeamFloorBlocks = [ ];
	public $redTeamFloorBlocks = [ ];
	
	public function __construct(KWPlugIn $plugin) {
		parent::__construct ( $plugin );
		$this->initBlockTypes ();
	}
	
	/**
	 * Initialize Building Blocks
	 */
	private function initBlockTypes() {
		$wallBlocks = $this->getSetup ()->getArenaBuildingBlocks ( KWSetup::KW_ARENA_WALL )->getAll ();
		$this->wallBlocksTypes = $wallBlocks ["blocks"];
		
		$floorBlocks = $this->getSetup ()->getArenaBuildingBlocks ( KWSetup::KW_ARENA_FLOOR )->getAll ();
		$this->floorBlocksTypes = $floorBlocks ["blocks"];
		
		$floorBlocksBlueTeam = $this->getSetup ()->getArenaBuildingBlocks ( KWSetup::KW_ARENA_FLOOR_BLUE_TEAM )->getAll ();
		$this->blueTeamFloorBlocks = $floorBlocksBlueTeam ["blocks"];
		
		$floorBlocksRedTeam = $this->getSetup ()->getArenaBuildingBlocks ( KWSetup::KW_ARENA_FLOOR_RED_TEAM )->getAll ();
		$this->redTeamFloorBlocks = $floorBlocksRedTeam ["blocks"];
	}
	
	/**
	 * render random blocks
	 *
	 * @param Block $block        	
	 * @param Player $p        	
	 */
	public function renderRandomBlocks2(Block $block, Level $level) {
		$b = array_rand ( $this->floorBlocksTypes );
		$blockType = $this->floorBlocksTypes [$b];
		$this->updateBlock2 ( $block, $level, $blockType );
	}
	public function renderRandomBlocks3(Block $block, Level $level) {
		$b = array_rand ( $this->wallBlocksTypes );
		$blockType = $this->wallBlocksTypes [$b];
		$this->updateBlock2 ( $block, $level, $blockType );
	}
	/**
	 * Render Blue Team Randomn Blocks
	 *
	 * @param Block $block        	
	 * @param Level $level        	
	 */
	public function renderBlueTeamRandomBlocks(Block $block, Level $level) {
		$b = array_rand ( $this->blueTeamFloorBlocks );
		$blockType = $this->blueTeamFloorBlocks [$b];
		// randomly place a mine
		if ($blockType == 21) {
			$this->updateBlock2 ( $block, $level, $blockType );
		} else {
			$this->updateBlock2 ( $block, $level, $blockType );
		}
	}
	/**
	 * Render Red Team Blocks
	 *
	 * @param Block $block        	
	 * @param Level $level        	
	 */
	public function renderRedTeamRandomBlocks(Block $block, Level $level) {
		$b = array_rand ( $this->redTeamFloorBlocks );
		$blockType = $this->redTeamFloorBlocks [$b];
		$this->updateBlock2 ( $block, $level, $blockType );
	}
	
	/**
	 * Building KW Arena
	 *
	 * @param Level $level        	
	 * @param  $floorwidth
	 * @param  $floorheight
	 * @param  $dataX
	 * @param  $dataY
	 * @param  $dataZ
	 * @param  $wallType
	 */
	public function plotArenaMap(Level $level, $floorwidth, $floorheight, $dataX, $dataY, $dataZ, $wallType) {
		$x = $dataX;
		$y = $dataY;
		$z = $dataZ;
		
		// create 5 blocks
		$this->buildBlueTeamArena ( $level, $floorwidth, $floorheight, $dataX, $dataY, $dataZ, $wallType );
		// build light bands
		$this->buildTeamArenaWall ( $level, $floorwidth, $floorheight - 1, $dataX, $dataY + 2, $dataZ + 1, 101 );
		$this->buildTeamArenaWall ( $level, $floorwidth, $floorheight - 4, $dataX, $dataY + 2, $dataZ + 1, 89 );
		$dataX = $dataX + $floorwidth;
		$this->buildRedTeamArena ( $level, $floorwidth, $floorheight, $dataX, $dataY, $dataZ, $wallType );
		// build light bands
		$this->buildTeamArenaWall ( $level, $floorwidth, $floorheight - 1, $dataX, $dataY + 2, $dataZ + 1, 101 );
		$this->buildTeamArenaWall ( $level, $floorwidth, $floorheight - 3, $dataX, $dataY + 2, $dataZ + 1, 89 );
		// erase the wall
		$this->removeArenaWall ( $level, $floorwidth - 1, $floorheight - 1, $dataX - 1, $dataY + 1, $dataZ, 1 );
		$this->markTeamBorder ( $level, $floorwidth, $dataX, $dataY, $dataZ );
		$this->closeTeamGates ( $level, $floorwidth, $dataX, $dataY, $dataZ );
		$this->addDefenceGates ( $level, $floorwidth, $dataX, $dataY, $dataZ );
		// blue team station
		$this->buildTeamArenaBase ( $level, 1, 1, $x + 1, $y + 3, ($z + 2), 21 );
		$this->buildTeamArenaBase ( $level, 3, 1, $x + 1, $y + 2, ($z + 2), 21 );
		$this->buildTeamArenaBase ( $level, 4, 1, $x + 1, $y + 1, ($z + 2), 21 );
		$this->buildTeamArenaBase ( $level, 6, 1, $x + 1, $y, ($z + 2), 21 );
		
		// blue team flag
		$this->addBlueTeamFlag ( $level, 171, 11 );
		// red team station
		$x = $dataX + $floorwidth - 6;
		$y = $dataY;
		$z = $dataZ;
		
		$this->buildTeamArenaBase ( $level, 6, 1, $x + 1, $y, ($z + 1), 74 );
		$this->buildTeamArenaBase ( $level, 4, 1, $x + 2, $y + 1, ($z + 1), 74 );
		$this->buildTeamArenaBase ( $level, 3, 1, $x + 3, $y + 2, ($z + 1), 74 );
		$this->buildTeamArenaBase ( $level, 1, 1, $x + 4, $y + 3, ($z + 1), 74 );
		
		// add red team flag
		$this->addRedTeamFlag ( $level, 171, 14 );
		$this->addGameButtons ( $level );
	}
	
	/**
	 * Build Border
	 *
	 * @param Level $level        	
	 * @param  $floorwidth
	 * @param  $dataX
	 * @param  $dataY
	 * @param  $dataZ
	 */
	public function markTeamBorder(Level $level, $floorwidth, $dataX, $dataY, $dataZ) {
		for($rz = 0; $rz < $floorwidth; $rz ++) {
			$cz = $dataZ + 26 - $rz;
			$rb = $level->getBlock ( new Vector3 ( $dataX, $dataY, $cz ) );
			$this->resetBlock ( $rb, $level, 8 );
		}
		$dataX = $dataX - 1;
		for($rz = 0; $rz < $floorwidth; $rz ++) {
			$cz = $dataZ + 26 - $rz;
			$rb = $level->getBlock ( new Vector3 ( $dataX, $dataY, $cz ) );
			$this->resetBlock ( $rb, $level, 8 );
		}
	}
	
	/**
	 * Build Closed Fence | Gate
	 *
	 * @param Level $level        	
	 * @param  $floorwidth
	 * @param  $dataX