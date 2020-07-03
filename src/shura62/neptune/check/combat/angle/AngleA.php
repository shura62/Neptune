<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\angle;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\MathUtils;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedInventoryTransactionPacket;

class AngleA extends Check {

    public function __construct() {
        parent::__construct("Angle", "NoLook", CheckType::COMBAT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::INVENTORY_TRANSACTION))
            return;
        $pk = new WrappedInventoryTransactionPacket($e->getPacket());
        $entity = $pk->entity;

        if($entity !== null && $user->desktop) {
            $player = $user->getPlayer();

            $vec = $entity->subtract(0, $entity->getY())->subtract($player->subtract(0, $player->getY()));

            $angle = MathUtils::angle($player->getDirectionVector(), $vec);
            if($angle > 1.135 && hypot($vec->abs()->getX(), $vec->abs()->getZ()) > 1 && $user->position->pitch <= 70) {
                if(++$this->vl > 3)
                    $this->flag($user, "angle= " . $angle);
            } else $this->vl = 0;
        }
    }

}