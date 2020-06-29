<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\speed;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class SpeedD extends Check {
    
    public function __construct() {
        parent::__construct("Speed", "D");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $dist = hypot($user->velocity->getX(), $user->velocity->getZ());
        $lastDist = hypot($user->lastVelocity->getX(), $user->lastVelocity->getZ());
        
        if ((($lastDist < 0.1 && $dist >= 0.287) || ($dist < 0.1 && $lastDist >= 0.287))
                && !PlayerUtils::hasBlocksAround($user->getPlayer()) && !$user->getPlayer()->getAllowFlight()) {
            if (++$this->vl > 2)
                $this->flag($user, "dist= " . $dist . ", lastDist= " . $lastDist);
        } else $this->vl-= $this->vl > 0 ? 0.25 : 0;
    }

}