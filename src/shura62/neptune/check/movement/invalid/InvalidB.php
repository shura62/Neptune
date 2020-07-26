<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\invalid;

use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class InvalidB extends Check {

    private $lastWasInventory;
    
    public function __construct() {
        parent::__construct("Invalid", "Sneak", CheckType::MOVEMENT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::INTERACT)) {
            $check = $e->getPacket()->action === InteractPacket::ACTION_OPEN_INVENTORY
                    && $user->getPlayer()->isSneaking();
            
            if ($check) {
                $this->lastWasInventory = true;
            }
        } else {
            if ($this->lastWasInventory) {
                $flag = !$e->equals(Packets::ACTION) || $e->getPacket()->action !== PlayerActionPacket::ACTION_STOP_SNEAK;
                
                if ($flag) {
                    $this->flag($user);
                }
            }
            
            $this->lastWasInventory = false;
        }
    }
    
}