<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\sprint;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class SprintA extends Check {

    public function __construct() {
        parent::__construct("Sprint", "Omni");
        $this->dev = true;
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        /*if(!$e->equals(Packets::MOVE))
            return;
        $move = $user->velocity->subtract(0, $user->velocity->getY());
        $predictedDelta = $move->distanceSquared($e->getPlayer()->getDirectionVector());

        if($predictedDelta > 1
                    && $user->collidedGround
                    && $user->getPlayer()->isSprinting()
                    && hypot($move->getX(), $move->getZ()) > 0.1
                    && $user->liquidTicks <= 0
                    && $user->cobwebTicks <= 0
                    && $user->lastKnockBack->hasPassed(20)) {
            if(++$this->vl > 4)
                $this->flag($user, "prediction= " . $predictedDelta);
        } else $this->vl = 0;*/
    }

}