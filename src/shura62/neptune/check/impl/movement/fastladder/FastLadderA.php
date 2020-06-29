<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\fastladder;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class FastLadderA extends Check {

    public function __construct() {
        parent::__construct("FastLadder", "A");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $velY = $user->velocity->getY();
        
        if ($velY > 0.21
                && PlayerUtils::isInClimbable($user->getPlayer())
                && $user->position->getY() > $user->lastPosition->getY()
                && microtime(true) - $user->lastKnockBack > 1) {
            if (++$this->vl > 2)
                $this->flag($user, "velY= " . $velY);
        } else $this->vl = 0;
    }
    
}