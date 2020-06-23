<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\speed;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;

class SpeedE extends Check {
    
    private $lastAccel;
    
    public function __construct() {
        parent::__construct("Speed", "E");
        $this->dev = true;
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $accelX = $user->velocity->getX();
        $accelZ = $user->velocity->getZ();
        $accel = ($accelX * $accelX) + ($accelZ * $accelZ);
        
        $lastAccel = $this->lastAccel;
        $this->lastAccel = $accel;
        
        $diff = abs($accel - $lastAccel) * 1000;
        
        if ($diff > 5
            && (($accel > 0 && $lastAccel < 0) || ($accel < 0 && $lastAccel > 0))
            && !$user->nearGround
            && !$user->getPlayer()->getAllowFlight()) {
            $this->flag($user, "accel= " . $diff);
        }
    }
    
}