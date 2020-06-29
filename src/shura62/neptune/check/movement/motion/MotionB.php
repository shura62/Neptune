<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\motion;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class MotionB extends Check {

    public function __construct() {
        parent::__construct("Motion", "SmallHop");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $motion = round($user->velocity->getY(), 5);
        $lastMotion = round($user->lastVelocity->getY(), 5);

        if($motion >= 0.035 && $motion < 0.04 && ($motion == -$lastMotion || $lastMotion == -$motion)) {
            $this->flag($user, "motionY= " . $motion);
        } else $this->vl = 0;
    }

}