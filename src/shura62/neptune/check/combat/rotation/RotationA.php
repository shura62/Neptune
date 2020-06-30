<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\rotation;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\MathUtils;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedInventoryTransactionPacket;

class RotationA extends Check {

    private $diffs = [];
    private $average = 100;
    private $lastDelta;

    public function __construct() {
        parent::__construct("Rotation", "Invalid");
        $this->dev = true;
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::INVENTORY_TRANSACTION))
            return;
        $pk = new WrappedInventoryTransactionPacket($e->getPacket());
        $entity = $pk->entity;

        if($entity !== null) {
            $delta = abs($user->position->getYaw() - $user->lastPosition->getYaw());
            $lastDelta = $this->lastDelta;

            $diff = abs($delta - $lastDelta);

            if($diff > 0)
                $this->diffs[] = $diff;

            if(count($this->diffs) >= 5) {
                $deviation = MathUtils::getStandardDeviation($this->diffs);
                $this->average = (($this->average * 19) + $deviation) / 20;

                if($this->average < 5) {
                    if(++$this->vl > 2)
                        $this->flag($user, "deviation= " . $deviation . ", average= " . $this->average);
                } else $this->vl = 0;

                $this->diffs = [];
            }
            $this->lastDelta = $delta;
        }
    }

}