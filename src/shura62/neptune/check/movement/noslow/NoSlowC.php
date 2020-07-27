<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\noslow;

use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class NoSlowC extends Check {

    public function __construct() {
        parent::__construct("NoSlow", "Food", CheckType::MOVEMENT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::ACTOR_EVENT)) {
            $event = $e->getPacket()->event;
            
            if ($event === ActorEventPacket::EATING_ITEM && $user->sprintingTicks > 2) {
                $this->flag($user);
            }
        } elseif ($e->equals(Packets::INVENTORY_TRANSACTION)) {
            $pk = $e->getPacket();
            $consuming = $pk->transactionType === InventoryTransactionPacket::TYPE_RELEASE_ITEM
                && $pk->trData->actionType === InventoryTransactionPacket::RELEASE_ITEM_ACTION_CONSUME;
            
            if ($consuming && $user->sprintingTicks > 2) {
                $this->flag($user);
            }
        }
    }
    
}