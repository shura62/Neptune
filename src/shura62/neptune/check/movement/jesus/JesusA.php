<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\jesus;

use pocketmine\item\enchantment\Enchantment;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\PlayerUtils;

class JesusA extends Check {

    public function __construct() {
        parent::__construct("Jesus", "Horizontal", CheckType::MOVEMENT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::MOVE) || $user->liquidTicks < 8) {
            return;
        }
        $deltaX = $user->velocity->getX();
        $deltaY = $user->velocity->getY();
        $deltaZ = $user->velocity->getZ();
        
        $onGround = $user->clientGround;
        $stationary = $deltaX % 1 == 0 && $deltaZ % 1 == 0;
       
        if (abs($deltaY) > 0 && !$onGround && $stationary
                && !$user->getPlayer()->getAllowFlight()
                && $user->lastKnockBack->hasPassed(60)) {
            // Check for depth strider
            $depthStrider = $user->getPlayer()
                ->getArmorInventory()
                ->getBoots()
                ->getEnchantment(Enchantment::DEPTH_STRIDER);
            $level = $depthStrider !== null ? $depthStrider->getLevel() : 0;
            
            $dist = hypot($deltaX, $deltaZ);
            $dist += $level * 0.08;
            
            if ($dist > 0.235) {
                $this->flag($user, "dist= " . $dist);
            }
        }
    }
    
}