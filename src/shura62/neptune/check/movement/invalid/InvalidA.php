<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\invalid;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class InvalidA extends Check {

    public function __construct() {
        parent::__construct("Invalid", "Pitch", CheckType::MOVEMENT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::MOVE))
            return;
        $pitch = $user->position->getPitch();

        if(abs($pitch) > 90)
            $this->flag($user, "pitch= " . $pitch);
    }

}