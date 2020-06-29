<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\packet\badpackets;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;

class BadPacketsA extends Check {

    public function __construct() {
        parent::__construct("BadPackets", "A");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        if (abs($user->position->getPitch()) > 90)
            $this->flag($user, "pitch= " . $user->position->getPitch());
    }
    
}