<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\motion;

use pocketmine\entity\Effect;
use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\PlayerUtils;

class MotionA extends Check {

    public function __construct() {
        parent::__construct("Motion", "Vertical");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::MOVE))
            return;
        if($user->airTicks > 4
                && $user->liquidTicks <= 0
                && $user->cobwebTicks <= 0
                && $user->lastKnockBack->hasNotPassed(20)) {
            $max = 0.7 + PlayerUtils::getPotionEffectLevel($user->getPlayer(), Effect::JUMP_BOOST) * 0.1;
            $deltaY = $user->velocity->getY();

            if($deltaY > $max)
                $this->flag($user, "deltaY= " . $deltaY);
        }
    }

}