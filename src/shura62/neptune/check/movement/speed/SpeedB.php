<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\speed;

use pocketmine\block\BlockIds;
use pocketmine\entity\Effect;
use shura62\neptune\check\Cancellable;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\PlayerUtils;

class SpeedB extends Check implements Cancellable {

    private $blockSlipperiness = 0.91;
    
    public function __construct() {
        parent::__construct("Speed", "Friction", CheckType::MOVEMENT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::MOVE))
            return;
        $dist = hypot($user->velocity->getX(), $user->velocity->getZ());
        $lastDist = hypot($user->lastVelocity->getX(), $user->lastVelocity->getZ());
        
        $friction = 0.91;
        $friction *= $this->blockSlipperiness;
        
        $prediction = $lastDist * $friction;
        $diff = abs($dist - $prediction);
        $scaledDist = $diff * 100;
        
        $max = 14.5 + (PlayerUtils::getPotionEffectLevel($e->getPlayer(), Effect::SPEED) * 0.2);

        if ($scaledDist > $max
                && $user->airTicks > 4
                && !$user->getPlayer()->getAllowFlight()) {
            if (++$this->vl > 3)
                $this->flag($user, "dist= " . $scaledDist);
        } else $this->vl = 0;
    
        if ($user->collidedGround) {
            $blockUnder = $user->position->getLevel()->getBlockAt(
                (int)floor($user->position->getX()),
                (int)floor($user->boundingBox->getMin()->getY() - 1),
                (int)floor($user->position->getZ())
            );
        
            $blockSlipperiness = 0.6;
            switch($blockUnder->getId()) {
                case BlockIds::PACKED_ICE:
                case BlockIds::ICE:
                    $blockSlipperiness = 0.98;
                    break;
                case BlockIds::FROSTED_ICE:
                    $blockSlipperiness = 0.989;
                    break;
                case BlockIds::SLIME:
                    $blockSlipperiness = 0.8;
                    break;
            }
        
            $this->blockSlipperiness = $blockSlipperiness;
        }
    }

}