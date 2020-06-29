<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\speed;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class SpeedA extends Check {
    
    public function __construct() {
        parent::__construct("Speed", "A");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $dist = hypot($user->velocity->getX(), $user->velocity->getZ());
        $lastDist = hypot($user->lastVelocity->getX(), $user->lastVelocity->getZ());
        
        $diff = abs($dist - $lastDist);
        
        if ($diff == 0
                && !$user->getPlayer()->getAllowFlight()
                && $dist > PlayerUtils::getBaseMovementSpeed($user)
                && $user->nearGround) {
            $this->flag($user, "dist= " . $dist);
        }
    }
    
}