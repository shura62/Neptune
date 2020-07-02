<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\fly;

use shura62\neptune\check\Cancellable;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class FlyC extends Check implements Cancellable {

    private $lastDist;

    public function __construct() {
        parent::__construct("Fly", "Prediction", CheckType::MOVEMENT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $dist = $user->position->getY() - $user->lastPosition->getY();
        $lastDist = $this->lastDist;
        $this->lastDist = $dist;

        $prediction = ($lastDist - 0.08) * 0.9800000190734863;

        if (!$e->getPlayer()->getAllowFlight()
                && $user->climbableTicks <= 0
                && $user->liquidTicks <= 0
                && $user->cobwebTicks <= 0
                && abs($prediction) > 0.005
                && $user->airTicks > 5
                && abs($prediction - $dist) > 0.005
                && $user->lastKnockBack->hasPassed(20)
                && $user->lastBlockPlace->hasPassed(20)) {
            if (++$this->vl > 3)
                $this->flag($user, "expected= " . $prediction . ", dist= " . $dist);
        } else $this->vl-= $this->vl > 0 ? 1 : 0;
    }

}