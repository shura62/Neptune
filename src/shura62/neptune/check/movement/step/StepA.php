<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\step;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class StepA extends Check {

    public function __construct() {
        parent::__construct("Step", "Vertical");
        $this->dev = true;
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if($e->equals(Packets::MOVE))
            return;
        $world = $user->position->level;
        if($world === null)
            return;
        $under = $world->getBlock($user->position->add(0, -0.5001));
        $deltaY = abs($user->velocity->getY());

        $box = $under->getBoundingBox();
        if($box !== null) {
            if ($deltaY > $box->maxY) {
                if(++$this->vl > 2)
                    $this->flag($user, "deltaY= " . $deltaY . ", max= " . $max);
            }
        }
    }

}