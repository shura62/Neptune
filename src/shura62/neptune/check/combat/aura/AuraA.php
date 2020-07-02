<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\aura;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedInventoryTransactionPacket;

class AuraA extends Check {

    private $tick;
    private $lastHit;

    public function __construct() {
        parent::__construct("Aura", "Multi", CheckType::COMBAT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::INVENTORY_TRANSACTION))
            return;
        $pk = new WrappedInventoryTransactionPacket($e->getPacket());
        $entity = $pk->entity;

        if ($entity !== null) {
            $curr = $user->getPlayer()->getServer()->getTick();
            if ($this->tick !== $curr) {
                $this->tick = $curr;
            } else {
                if ($this->lastHit !== $entity) {
                    if (++$this->vl > 4)
                        $this->flag($user, "tick= " . $curr);
                } else $this->vl = 0;
            }

            $this->lastHit = $entity;
        }
    }

}