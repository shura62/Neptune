<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\noswing;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedAnimatePacket;
use shura62\neptune\utils\packet\types\WrappedInventoryTransactionPacket;
use shura62\neptune\utils\Timestamp;

class NoSwingA extends Check {

    private $lastSwing;

    public function __construct() {
        parent::__construct("NoSwing", "Packet");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if($e->equals(Packets::ANIMATE)) {
            if((new WrappedAnimatePacket($e->getPacket()))->swung) {
                if($this->lastSwing !== null) {
                    $this->lastSwing->reset();
                } else $this->lastSwing = new Timestamp();
            }
        } elseif($e->equals(Packets::INVENTORY_TRANSACTION)) {
            if(($this->lastSwing === null || $this->lastSwing->hasPassed(10))
                        && (new WrappedInventoryTransactionPacket($e->getPacket()))->entity !== null) {
                if(++$this->vl > 2)
                    $this->flag($user, "ticks= " . ($this->lastSwing->getPassed()));
            } else $this->vl = 0;
        }
    }

}