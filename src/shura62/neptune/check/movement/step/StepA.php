<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\step;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class StepA extends Check {

    public function __construct() {
        parent::__construct("Step", "Vertical", CheckType::MOVEMENT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::MOVE))
            return;
        $world = $user->position->level;
        if ($world === null)
            return;
        $under = $world->getBlock($user->position->add(0, -($user->getPlayer()->getEyeHeight() + 0.5001)));
        $deltaY = $user->lastVelocity->getY();

        $box = $under->getBoundingBox();

        if($box !== null) {
            $max = ($box->maxY - $box->minY) - 0.5001;
            if ($user->collidedGround
                    && $max > 0
                    && $deltaY > $max
                    && $user->lastKnockBack->hasPassed(20)
                    && ($user->lastMoveFlag === null || $user->lastMoveFlag->hasPassed(20))) {
                $this->flag($user);
            }
        }
    }

}