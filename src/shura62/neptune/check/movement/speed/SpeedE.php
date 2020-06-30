<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\speed;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\boundingbox\BoundingBox;
use shura62\neptune\utils\packet\Packets;

class SpeedE extends Check {

    private $lastNotCollided;
    private $lastTicks;

    public function __construct() {
        parent::__construct("Speed", "Collisions");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $world = $user->position->level;
        if ($world === null)
            return;

        $block = $world->getBlock($user->position->add(0, -$user->getPlayer()->getEyeHeight()));
        $bb = $block->getBoundingBox();
        if($bb === null)
            return;
        $box = BoundingBox::from($bb);

        $collided = $box->collides($user->boundingBox);
        $speed = hypot($user->velocity->x, $user->velocity->z);

        if(!$collided) {
            $lastTick = $this->lastNotCollided;
            $tick = $user->getPlayer()->getServer()->getTick();

            $ticks = $tick - $lastTick;
            $lastTicks = $this->lastTicks;

            if($ticks > 3 && $ticks == $lastTicks) {
                $this->flag($user, "ticks= " . $ticks);
            } else $this->vl = 0;

            $this->lastTicks = $ticks;
            $this->lastNotCollided = $tick;
        }
    }

}