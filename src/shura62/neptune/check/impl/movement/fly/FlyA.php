<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\fly;

use pocketmine\Player;
use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class FlyA extends Check {

    private $lastGround;
    
    public function __construct() {
        parent::__construct("Fly", "A");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        if ($user->nearGround) {
            $this->lastGround = $user->position->getY();
        } else {
            if ($user->teleportTicks > 0 || $user->getPlayer()->getAllowFlight() || PlayerUtils::isInClimbable($e->getPlayer()) || PlayerUtils::isInLiquid($e->getPlayer())) {
                $this->vl = 0;
                return;
            }
            
            $dist = $user->position->getY() - $this->lastGround;
            //$vel = $user->position->getY() - $user->lastPosition->getY();
            
            if ($dist >= 1.13 && $user->position->getY() >= $user->lastPosition->getY()) {
                if (++$this->vl > 9)
                    $this->flag($user, "curY= " . $user->position->getY() . ", lastGround= " . $this->lastGround);
            } else $this->vl = 0;
        }
    }
    
}
