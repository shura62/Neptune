<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\fly;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class FlyA extends Check {

    private $lastGround;

    public function __construct() {
        parent::__construct("Fly", "Height");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        if ($user->collidedGround) {
            $this->lastGround = $user->position->getY();
        } else {
            if ($user->getPlayer()->getAllowFlight() || $user->liquidTicks > 0 || $user->climbableTicks > 0) {
                $this->vl = 0;
                return;
            }

            $dist = $user->position->getY() - $this->lastGround;

            if ($dist >= 1.13 && $user->position->getY() >= $user->lastPosition->getY() && $user->lastKnockBack->hasPassed(20)) {
                if (++$this->vl > 9)
                    $this->flag($user, "curY= " . $user->position->getY() . ", lastGround= " . $this->lastGround);
            } else $this->vl = 0;
        }
    }

}