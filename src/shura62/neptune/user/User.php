<?php

declare(strict_types=1);

namespace shura62\neptune\user;

use pocketmine\network\mcpe\protocol\NetworkStackLatencyPacket;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use shura62\neptune\check\CheckManager;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\processing\types\ClickProcessor;
use shura62\neptune\processing\types\DeviceProcessor;
use shura62\neptune\processing\types\KeyProcessor;
use shura62\neptune\processing\types\MovementProcessor;
use shura62\neptune\processing\types\PacketProcessor;
use shura62\neptune\utils\Timestamp;

class User {

    private $player;

    public $boundingBox;
    public $position, $lastPosition, $lastGroundPosition;
    public $velocity, $lastVelocity;
    public $online, $clientGround, $collidedGround, $alerts, $inventoryOpen, $digging, $desktop, $sprinting;
    public $movementProcessor, $packetProcessor, $deviceProcessor, $clickProcessor, $keyProcessor;
    public $flagDelay, $airTicks, $groundTicks, $iceTicks, $slimeTicks, $liquidTicks, $climbableTicks, $cobwebTicks, $blocksAboveTicks, $sprintingTicks;
    public $cps, $ping;
    public $lastTeleport, $lastKnockBack, $lastBlockPlace, $lastMoveFlag;
    public $checks = [];

    public function __construct(Player $player) {
        $this->player = $player;

        $this->movementProcessor = new MovementProcessor($this);
        $this->packetProcessor = new PacketProcessor($this);
        $this->deviceProcessor = new DeviceProcessor($this);
        $this->clickProcessor = new ClickProcessor($this);
        $this->keyProcessor = new KeyProcessor($this);

        $this->flagDelay = 1;

        $this->checks = new CheckManager();

        $this->lastTeleport = new Timestamp();
        $this->lastKnockBack = new Timestamp();
        $this->lastBlockPlace = new Timestamp();
        
        /*NeptunePlugin::getInstance()->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (int $currentTick) use ($player) : void{
            if ($player->isOnline()) {
                $packet = new NetworkStackLatencyPacket();
                $packet->needResponse = true;
                $packet->timestamp = microtime(true) / 1000;
    
                $player->directDataPacket($packet);
            }
        }), 1);*/
    }

    public function getPlayer() : Player{
        return $this->player;
    }

}