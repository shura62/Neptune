<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\combat\reach;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\packet\impl\WrappedInventoryTransactionPacket;
use shura62\neptune\utils\Ray;

class ReachA extends Check {
    
    public function __construct() {
        parent::__construct("Reach", "A");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::INVENTORY_TRANSACTION))
            return;
        $packet = new WrappedInventoryTransactionPacket($e->getPlayer(), $e->getPacket());
        
        $p = $e->getPlayer();
        $entity = $packet->getEntity();
        
        $eyes = $p->add(0, $p->getEyeHeight());
        $direction = $p->getDirectionVector();
        $ray = new Ray($eyes, $direction);
        
        
    }
    
}