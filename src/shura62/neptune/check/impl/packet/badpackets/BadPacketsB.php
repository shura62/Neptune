<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\packet\badpackets;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;

class BadPacketsB extends Check {
    
    public function __construct() {
        parent::__construct("BadPackets", "B");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        if (($user->velocity->getX() >= 10
                || $user->velocity->getY() >= 10
                || $user->velocity->getZ() >= 10)
            && microtime(true) - $user->lastTeleport > 2) {
            if (++$this->vl > 1)
                $this->flag($user);
        } else $this->vl = 0;
    }
    
}