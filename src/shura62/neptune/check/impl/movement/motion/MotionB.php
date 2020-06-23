<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\motion;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class MotionB extends Check {
    
    private $lastVelY;
    
    public function __construct() {
        parent::__construct("Motion", "B");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $velY = $user->position->getY() - $user->lastPosition->getY();
        $lastVelY = $this->lastVelY;
        $this->lastVelY = $velY;
        
        if ($lastVelY < 0 && $velY >= $e->getPlayer()->getJumpVelocity()
                && !$e->getPlayer()->getAllowFlight()
                && $e->getPlayer()->getInAirTicks() > 12
                && !$user->nearGround
                && !PlayerUtils::isInClimbable($e->getPlayer())
                && !PlayerUtils::isInLiquid($e->getPlayer())
                && microtime(true) - $user->lastKnockBack >= 1.2) {
            $this->flag($user, "velY= " . $velY . ", lastVelY= " . $lastVelY);
        }
    }
    
}