<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\aura;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\MathUtils;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedInventoryTransactionPacket;

class AuraB extends Check {

    public function __construct() {
        parent::__construct("Aura", "Lockview", CheckType::COMBAT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::INVENTORY_TRANSACTION))
            return;
        $pk = new WrappedInventoryTransactionPacket($e->getPacket());
        $entity = $pk->entity;

        if($entity !== null) {
            $rotation = abs($user->position->getYaw() - $user->lastPosition->getYaw());

            $direction = MathUtils::getDirectionFromVectors($user->position, $entity);
            $dist = MathUtils::getDistanceBetweenAngles($user->position->getYaw(), $direction);

            if($dist < 0.7 && $rotation > 2) {
                if(++$this->vl > 1)
                    $this->flag($user, "direction= " . $direction . ", rotation= " . $rotation);
            } else $this->vl = 0;
        }
    }

}