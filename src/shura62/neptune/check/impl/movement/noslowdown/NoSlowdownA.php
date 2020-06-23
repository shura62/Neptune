<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\noslowdown;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class NoSlowdownA extends Check {

    public function __construct() {
        parent::__construct("NoSlowdown", "A");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $dist = hypot($user->velocity->getX(), $user->velocity->getZ());
        $max = $user->getPlayer()->isSprinting() ? 0.032 : 0.025;
        
        if ($dist > $max
                && PlayerUtils::isInCobweb($user->getPlayer())
                && microtime(true) - $user->lastTeleport > 1) {
            if (++$this->vl > 3)
                $this->flag($user, "dist= " . $dist);
        } else $this->vl = 0;
    }
    
}