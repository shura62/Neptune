<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\motion;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class MotionC extends Check {

    private $lastDeltaY;
    
    public function __construct() {
        parent::__construct("Motion", "C");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $deltaY = $user->velocity->getY();
        $lastDeltaY = $this->lastDeltaY;
        $this->lastDeltaY = $deltaY;
        
        if (round($deltaY, 10) == round($lastDeltaY, 10)
                    && !$e->getPlayer()->getAllowFlight()
                    && !$user->nearGround
                    && $user->airTicks <= 1
                    && !PlayerUtils::isInClimbable($e->getPlayer())
                    && !PlayerUtils::isInLiquid($e->getPlayer())) {
                        if (++$this->vl > 2)
                           $this->flag($user, "deltaY= " . $deltaY . ", lastDeltaY= " . $lastDeltaY);
        }
    }
    
}