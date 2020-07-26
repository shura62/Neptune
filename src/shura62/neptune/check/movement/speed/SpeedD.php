<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\speed;

use shura62\neptune\check\Cancellable;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class SpeedD extends Check implements Cancellable {

    private $lastAccel;

    public function __construct() {
        parent::__construct("Speed", "Acceleration", CheckType::MOVEMENT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $accelX = $user->velocity->getX();
        $accelZ = $user->velocity->getZ();
        $accel = hypot($accelX, $accelZ);

        $lastAccel = $this->lastAccel;
        $this->lastAccel = $accel;

        if(($accel > 0.29 && $lastAccel <= 0) || ($accel <= 0 && $lastAccel > 0.29)
                && ($user->lastMoveFlag === null || $user->lastMoveFlag->hasPassed(1))
                && $user->lastKnockBack->hasPassed(40)
                && !$user->getPlayer()->getAllowFlight()) {
            if(++$this->vl > 2) {
                $this->flag($user, "accel= " . $accel . ", lastAccel= " . $lastAccel);
            }
        } else $this->vl = 0;
    }

}