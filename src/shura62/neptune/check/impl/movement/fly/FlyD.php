<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\fly;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\packet\impl\WrappedMovePlayerPacket;
use shura62\neptune\utils\PlayerUtils;

class FlyD extends Check {
    
    public function __construct() {
        parent::__construct("Fly", "D");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $packet = new WrappedMovePlayerPacket($e->getPlayer(), $e->getPacket());
        
        $curY = $user->position->getY();
        $lastY = $user->lastPosition->getY();
        
        if ($packet->isPos()
                && !PlayerUtils::isInClimbable($user->getPlayer())
                && !$user->getPlayer()->getAllowFlight()
                && $curY === $lastY
                && $e->getPlayer()->getInAirTicks() > 4) {
            if (++$this->vl > 4)
                $this->flag($user);
        } else $this->vl = 0;
    }
    
}