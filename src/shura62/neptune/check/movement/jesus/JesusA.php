<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\jesus;

use pocketmine\item\enchantment\Enchantment;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\BlockUtils;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\PlayerUtils;

class JesusA extends Check {
    
    private $liquidTicks = 0;
    
    public function __construct() {
        parent::__construct("Jesus", "Horizontal", CheckType::MOVEMENT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::MOVE)) {
            return;
        }
        
        if ($user->liquidTicks > 0 && BlockUtils::isLiquid($user->position->level->getBlockAt(
                (int) floor($user->position->getX()),
                (int) floor($user->boundingBox->getMin()->getY() - 0.3),
                (int) floor($user->position->getZ())
            ))) {
            ++$this->liquidTicks;
        } else {
            $this->liquidTicks = 0;
        }
        
        $deltaX = $user->velocity->getX();
        $deltaY = $user->velocity->getY();
        $deltaZ = $user->velocity->getZ();
        
        $stationary = $deltaX % 1 == 0 && $deltaZ % 1 == 0;
        $onGround = $user->clientGround;
        
        if (abs($deltaY) > 0 && !$onGround && $stationary
                && !$user->getPlayer()->getAllowFlight()
                && $user->lastKnockBack->hasPassed(60)
                && $this->liquidTicks > 8) {
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