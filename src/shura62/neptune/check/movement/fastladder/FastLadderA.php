<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\fastladder;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class FastLadderA extends Check {

    public function __construct() {
        parent::__construct("FastLadder", "Velocity");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::MOVE))
            return;
        $deltaY = $user->velocity->getY();
        $lastDeltaY = $user->lastVelocity->getY();

        if($deltaY >= 0.2
                && $user->climbableTicks > 4
                && $user->lastKnockBack->hasPassed(20)
                && $deltaY == $lastDeltaY
                && !$user->getPlayer()->getAllowFlight()) {
            if(++$this->vl > 3)
                $this->flag($user, "deltaY= " . $deltaY);
        } else $this->vl = 0;
    }

}