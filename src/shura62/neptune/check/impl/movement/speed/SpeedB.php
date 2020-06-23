<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\speed;

use pocketmine\entity\Effect;
use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\block\WrappedBlock;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\PlayerUtils;

class SpeedB extends Check {

    private $blockUnder;
    
    public function __construct() {
        parent::__construct("Speed", "B");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $dist = hypot($user->velocity->getX(), $user->velocity->getZ());
        $lastDist = hypot($user->lastVelocity->getX(), $user->lastVelocity->getZ());
        
        $prediction = $lastDist * 0.699999988079071;
        $diff = abs($dist - $prediction);
        $scaledDist = $diff * 100;
        
        $max = 11 + (PlayerUtils::getPotionEffectLevel($e->getPlayer(), Effect::SPEED) * 0.2);
    
        if ($scaledDist > $max
                && $user->airTicks > 4
                && !$user->getPlayer()->getAllowFlight()) {
            if (++$this->vl > 4)
                $this->flag($user, "dist=" . $scaledDist);
        } else $this->vl = 0;
    }
    
}