<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\noslow;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class NoSlowA extends Check {

    public function __construct() {
        parent::__construct("NoSlow", "Cobweb");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::MOVE))
            return;
        $dist = hypot($user->velocity->getX(), $user->velocity->getZ());
        $max = $user->getPlayer()->isSprinting() ? 0.032 : 0.025;

        if ($dist > $max && $user->cobwebTicks > 2 && $user->lastKnockBack->hasPassed(20)) {
            if (++$this->vl > 3)
                $this->flag($user, "dist= " . $dist);
        } else $this->vl = 0;
    }

}