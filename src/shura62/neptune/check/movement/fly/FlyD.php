<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\fly;

use shura62\neptune\check\Cancellable;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class FlyD extends Check implements Cancellable {

    public function __construct() {
        parent::__construct("Fly", "Horizontal", CheckType::MOVEMENT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $curY = $user->position->getY();
        $lastY = $user->lastPosition->getY();

        $deltaXZ = hypot($user->velocity->getX(), $user->velocity->getZ());

        if ($deltaXZ !== 0
                && $user->climbableTicks <= 0
                && !$user->getPlayer()->getAllowFlight()
                && $curY === $lastY
                && $user->airTicks > 4
                && $user->lastKnockBack->hasPassed(20)
                && $user->lastBlockPlace->hasPassed(20)) {
            if (++$this->vl > 4)
                $this->flag($user);
        } else $this->vl = 0;
    }

}