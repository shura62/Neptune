<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\fly;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class FlyB extends Check {

    private $lastVelY;
    
    public function __construct() {
        parent::__construct("Fly", "B");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $velY = $user->position->getY() - $user->lastPosition->getY();
        $lastVelY = $this->lastVelY;
        $this->lastVelY = $velY;
    
        $dist = $velY - $lastVelY;
    
        if ($dist >= 0
                && !$user->clientGround
                && !$e->getPlayer()->getAllowFlight()
                && !PlayerUtils::isInLiquid($e->getPlayer())
                && !PlayerUtils::isInClimbable($e->getPlayer())
                && !PlayerUtils::isInCobweb($e->getPlayer())) {
            if (++$this->vl > 10)
                $this->flag($user, "dist= " . $dist);
        } else $this->vl = 0;
    }
    
}