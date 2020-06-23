<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet\impl;

use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\Player;
use shura62\neptune\utils\packet\WrappedPacket;

class WrappedMovePlayerPacket extends WrappedPacket {

    private $location;
    private $pos, $look;
    
    public function __construct(Player $player, MovePlayerPacket $pk) {
        parent::__construct($player, $pk);
    }
    
    protected function process() {
        $pk = $this->getPacket();
        $player = $this->getPlayer();
        
        $pos = $pk->position;
        $this->location = new Location($pos->x, $pos->y, $pos->z, $pk->yaw, $pk->pitch, $player->level);
        $this->pos = !$pos->equals(new Vector3($player->lastX, $player->lastY, $player->lastZ));
        $this->look = $pk->yaw == $player->lastYaw || $pk->pitch == $player->lastPitch;
    }
    
    public function isPos() : bool{
        return $this->pos;
    }
    
    public function isLook() : bool{
        return $this->look;
    }
    
    public function getLocation() : Location{
        return $this->location;
    }
    
}