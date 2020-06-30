<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\speed;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\MathUtils;
use shura62\neptune\utils\packet\Packets;

class SpeedC extends Check {

    public function __construct() {
        parent::__construct("Speed", "Gcd");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $dist = hypot($user->velocity->getX(), $user->velocity->getZ());
        $lastDist = hypot($user->lastVelocity->getX(), $user->lastVelocity->getZ());

        $diff = $dist - $lastDist;

        if ($diff == 0)
            return;

        $gcd = MathUtils::gcd((int) floor($dist), (int) floor($lastDist)) / $diff;

        if ($gcd > 0
                && !$user->getPlayer()->getAllowFlight()) {
            if (++$this->vl > 2)
                $this->flag($user, "dist= " . $gcd);
        } else $this->vl = 0;
    }

}