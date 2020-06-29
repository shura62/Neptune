<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\packet\badpackets;

use pocketmine\network\mcpe\protocol\AnimatePacket;
use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;
use shura62\neptune\utils\packet\impl\WrappedInventoryTransactionPacket;

class BadPacketsC extends Check {
    
    private $lastSwing;
    
    public function __construct() {
        parent::__construct("BadPackets", "C");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equalsPacketType(Client::INVENTORY_TRANSACTION)) {
            $packet = new WrappedInventoryTransactionPacket($e->getPlayer(), $e->getPacket());
            $entity = $packet->getEntity();
            $elapsed = microtime(true) - $this->lastSwing;
            $max = ($e->getPlayer()->getPing() / 1000) + 1;
        
            if ($this->lastSwing === 0 || $elapsed > $max) {
                if (++$this->vl > 2)
                    $this->flag($user, "elapsed= " . $elapsed);
            } else $this->vl = 0;
        } elseif ($e->equalsPacketType(Client::ANIMATE)) {
            if ($e->getPacket()->action === AnimatePacket::ACTION_SWING_ARM) {
                $this->lastSwing = microtime(true);
            }
        }
    }
    
}