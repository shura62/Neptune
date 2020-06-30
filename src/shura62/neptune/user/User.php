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
    public $position, $lastPosition;
    public $velocity, $lastVelocity;
    public $clientGround, $collidedGround, $alerts, $inventoryOpen, $digging, $desktop;
    public $movementProcessor, $packetProcessor, $deviceProcessor;
    public $flagDelay, $airTicks, $groundTicks, $iceTicks, $slimeTicks, $liquidTicks, $climbableTicks, $cobwebTicks, $blocksAboveTicks;
    public $lastTeleport, $lastKnockBack;
    public $checks = [];

    public function __construct(Player $player) {
        $this->player = $player;

        $this->movementProcessor = new MovementProcessor();
        $this->packetProcessor = new PacketProcessor();
        $this->deviceProcessor = new DeviceProcessor();

        $this->flagDelay = 1;

        $this->checks = new CheckManager();

        $this->lastTeleport = new Timestamp();
        $this->lastKnockBack = new Timestamp();
    }

    public function getPlayer() : Player{
        return $this->player;
    }

}