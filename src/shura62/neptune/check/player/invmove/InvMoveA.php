<?php

declare(strict_types=1);

namespace shura62\neptune\check\player\invmove;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class InvMoveA extends Check {

    public function __construct() {
        parent::__construct("InvMove", "Invalid", CheckType::PLAYER);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $deltaXZ =  hypot($user->velocity->getX(), $user->velocity->getZ());

        if($user->inventoryOpen
                && $user->cobwebTicks <= 0
                && $user->liquidTicks <= 0
                && $user->climbableTicks <= 0
                && $user->lastKnockBack->hasPassed(40)
                && $user->groundTicks > 4
                && $deltaXZ > 0.1) {
            if(++$this->vl > 2)
                $this->flag($user, "deltaXZ= " . $deltaXZ);
        } else $this->vl = 0;
    }

}