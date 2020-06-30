<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\fly;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class FlyE extends Check {

    private $lastWasNegative;
    private $ticks;

    public function __construct() {
        parent::__construct("Fly", "Jump");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $deltaY = $user->velocity->getY();

        if(!$user->collidedGround) {
            if ($deltaY >= 0) {
                $negative = $this->lastWasNegative;

                if ($negative) {
                    if (++$this->ticks < 4
                            && $user->cobwebTicks <= 0
                            && $user->liquidTicks <= 0
                            && $user->climbableTicks <= 0
                            && $user->lastKnockBack->hasPassed(20)
                            && !$user->getPlayer()->getAllowFlight()) {
                        $this->flag($user, "lastWasNegative= " . ($negative ? "true" : "false"));
                        $this->ticks = 0;
                    }
                }
                $this->lastWasNegative = false;
            } else {
                $this->lastWasNegative = true;
                $this->ticks = 0;
            }
        } else $this->lastWasNegative = false;
    }

}