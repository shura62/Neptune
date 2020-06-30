<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\fly;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class FlyB extends Check {

    private $lastVelY;

    public function __construct() {
        parent::__construct("Fly", "Ascension");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $velY = $user->position->getY() - $user->lastPosition->getY();
        $lastVelY = $this->lastVelY;
        $this->lastVelY = $velY;

        $dist = $velY - $lastVelY;

        if ($dist >= 0
                && !$user->collidedGround
                && !$e->getPlayer()->getAllowFlight()
                && $user->liquidTicks <= 0
                && $user->climbableTicks <= 0
                && $user->cobwebTicks <= 0
                && $user->lastKnockBack->hasPassed(20)) {
            if (++$this->vl > 10)
                $this->flag($user, "dist= " . $dist);
        } else $this->vl = 0;
    }

}