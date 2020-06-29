<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\motion;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;

class MotionA extends Check {
    
    private $lastDist;
    
    public function __construct() {
        parent::__construct("Motion", "A");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $dist = $user->position->getY() - $user->lastPosition->getY();
        $lastDist = $this->lastDist;
        $this->lastDist = $dist;
        
        if ($dist >= 1 && $lastDist == 0 && $user->velocity->getY() > 0.075) {
            $this->flag($user, "dist= " . $dist . ", lastDist= " . $lastDist);
        }
    }
    
}