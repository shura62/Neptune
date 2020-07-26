<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\speed;

use pocketmine\entity\Effect;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\PlayerUtils;

class SpeedE extends Check {
    
    private $lastOnGround;
    private $lastWasMove;
    
    public function __construct() {
        parent::__construct("Speed", "Hand", CheckType::MOVEMENT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::MOVE)) {
            if (!$e->equals(Packets::INVENTORY_TRANSACTION)) {
                $this->lastWasMove = false;
            }
            return;
        }
        $deltaX = $user->velocity->getX();
        $deltaZ = $user->velocity->getZ();
        
        $maxSpeed = 0.5 + PlayerUtils::getPotionEffectLevel($user->getPlayer(), Effect::SPEED) * 0.08;
    
        $onGround = $user->collidedGround;
        $sneaking = $user->getPlayer()->isSneaking();
        
        if ($sneaking && $this->lastWasMove && !$user->getPlayer()->getAllowFlight()) {
            $differentY = abs($user->position->getY() - $user->lastPosition->getY()) > 2;
            $horizontalSpeed = hypot($deltaX, $deltaZ);
            
            // Player changed Y-axis coordinate being on ground both ticks.
            // He apparently teleported between two positions.
            $flag = $this->lastOnGround && $onGround && $user->position->getY() && $differentY && $horizontalSpeed > 0.2;
            
            if ($horizontalSpeed > $maxSpeed && $maxSpeed > 0 || $flag) {
                $this->flag($user);
            }
        }
    
        $this->lastOnGround = $onGround;
        $this->lastWasMove = true;
    }
    
}