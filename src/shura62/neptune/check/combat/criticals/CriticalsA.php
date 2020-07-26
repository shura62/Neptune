<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\criticals;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedInventoryTransactionPacket;

class CriticalsA extends Check {

    public function __construct() {
        parent::__construct("Criticals", "Packet", CheckType::COMBAT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::INVENTORY_TRANSACTION)) {
            $pk = new WrappedInventoryTransactionPacket($e->getPacket());
            $entity = $pk->entity;
            
            if ($entity !== null) {
                if (!$user->clientGround && $user->collidedGround) {
                    if (++$this->vl > 2) {
                        $this->flag($user);
                    }
                } else $this->vl = 0;
            }
        }
    }
    
}