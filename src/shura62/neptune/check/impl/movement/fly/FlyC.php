<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\fly;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class FlyC extends Check {

    private $lastDist;
    
    public function __construct() {
        parent::__construct("Fly", "C");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $dist = $user->position->getY() - $user->lastPosition->getY();
        $lastDist = $this->lastDist;
        $this->lastDist = $dist;
        
        $expected = ($lastDist - 0.08) * 0.98;
        
        if (!$e->getPlayer()->getAllowFlight()
                    && !PlayerUtils::isInClimbable($user->getPlayer())
                    && !PlayerUtils::isInCobweb($user->getPlayer())
                    && abs($expected) > 0.005
                    && $e->getPlayer()->getInAirTicks() > 5
                    && abs($expected - $dist) > 0.005) {
            if (++$this->vl > 3)
                $this->flag($user, "expected= " . $expected . ", dist= " . $dist);
        } else $this->vl-= $this->vl > 0 ? 1 : 0;
    }
    
}