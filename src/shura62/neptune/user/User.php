<?php

declare(strict_types=1);

namespace shura62\neptune\user;

use pocketmine\Player;
use shura62\neptune\check\CheckManager;
use shura62\neptune\processing\types\DeviceProcessor;
use shura62\neptune\processing\types\MovementProcessor;
use shura62\neptune\processing\types\PacketProcessor;
use shura62\neptune\utils\Timestamp;

class User {

    private $player;

    public $boundingBox;
    public $position, $lastPosition, $lastGroundPosition;
    public $velocity, $lastVelocity;
    public $clientGround, $collidedGround, $alerts, $inventoryOpen, $digging, $desktop, $sprinting;
    public $movementProcessor, $packetProcessor, $deviceProcessor;
    public $flagDelay, $airTicks, $groundTicks, $iceTicks, $slimeTicks, $liquidTicks, $climbableTicks, $cobwebTicks, $blocksAboveTicks, $sprintingTicks;
    public $lastTeleport, $lastKnockBack, $lastBlockPlace, $lastMoveFlag;
    public $checks = [];

    public function __construct(Player $player) {
        $this->player = $player;

        $this->movementProcessor = new MovementProcessor($this);
        $this->packetProcessor = new PacketProcessor($this);
        $this->deviceProcessor = new DeviceProcessor($this);

        $this->flagDelay = 1;

        $this->checks = new CheckManager();

        $this->lastTeleport = new Timestamp();
        $this->lastKnockBack = new Timestamp();
        $this->lastBlockPlace = new Timestamp();
    }

    public function getPlayer() : Player{
        return $this->player;
    }

}