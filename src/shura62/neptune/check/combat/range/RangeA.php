<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\range;

use pocketmine\Player;
use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\user\UserManager;
use shura62\neptune\utils\boundingbox\AABB;
use shura62\neptune\utils\boundingbox\Ray;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedInteractPacket;
use shura62\neptune\utils\packet\types\WrappedInventoryTransactionPacket;

// Still in development

class RangeA extends Check {

    private $lastAttack;
    private $interaction, $tick;

    public function __construct() {
        parent::__construct("Range", "Distance");
        $this->dev = true;
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if($e->equals(Packets::INVENTORY_TRANSACTION)) {
            $pk = new WrappedInventoryTransactionPacket($e->getPacket());
            $entity = $pk->entity;

            if($entity instanceof Player
                    && ($target = UserManager::get($entity)) !== null
                    && $this->interaction !== null
                    && $user->desktop) {
                $hit = $this->lastAttack;
                $now = microtime(true);

                if($now - $hit < 0.05)
                    return;
                $this->lastAttack = $now;

                $dist = AABB::from($target)->collidesRay(Ray::from($user), 0, 10);
                $inter = $this->interaction;

                if($dist == -1 || $dist >= 3.5 || $inter > 3.5)
                    return;

                $threshold = $user->getPlayer()->isCreative()
                        ? 6
                        : 3.01;

                //$user->getPlayer()->sendMessage("d: " . $dist . "; i: " . $inter);

                if($inter >= $threshold && $dist > $threshold) {
                    $this->flag($user);
                }
            }
        } elseif($e->equals(Packets::INTERACT)) {
            $pk = new WrappedInteractPacket($e->getPacket());
            $target = $pk->target;

            if($target !== null) {
                $this->interaction =
                    $user->position->distance($target) - 0.3;
                $this->tick = $user->getPlayer()->getServer()->getTick();
            } else {
                $this->interaction = null;
            }
        }
    }

}