<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\range;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Player;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\user\UserManager;
use shura62\neptune\utils\boundingbox\AABB;
use shura62\neptune\utils\boundingbox\Ray;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedInteractPacket;
use shura62\neptune\utils\packet\types\WrappedInventoryTransactionPacket;

class RangeB extends Check implements Listener {

    private $boxes = [];
    private $lastTarget, $lastAttack;

    public function __construct() {
        parent::__construct("Range", "Hitbox", CheckType::COMBAT);
        NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::INVENTORY_TRANSACTION)) {
            $pk = new WrappedInventoryTransactionPacket($e->getPacket());
            $entity = $pk->entity;

            if ($entity instanceof Player && $user->desktop) {
                $target = UserManager::get($entity);
                if($target === null)
                    return;
                $this->lastTarget = $target;

                $hit = $this->lastAttack;
                $now = microtime(true);

                if($now - $hit < 0.2)
                    return;
                $this->lastAttack = $now;

                $ray = Ray::from($user);
                if(count($this->boxes) == 10) {
                    $collisions = [];
                    foreach($this->boxes as $box) {
                        $collision = $box->collidesRay($ray, 0, 8);
                        if($collision != -1)
                            $collisions[] = $collision;
                    }

                    if(count($collisions) == 0) {
                        // Hitbox?
                        return;
                    }
                    $dist = min($collisions);
                    $max = $user->getPlayer()->isCreative() ? 6 : 3.2;

                    if($dist > $max) {
                        if(++$this->vl > 1)
                            $this->flag($user, "dist= " . $dist);
                    } else $this->vl-= $this->vl > 0 ? 1 : 0 ;
                }
            }
        }
    }

    public function onMove(PlayerMoveEvent $event) : void{
        $player = $event->getPlayer();
        $user = UserManager::get($player);

        if($user === $this->lastTarget && $user !== null) {
            if(count($this->boxes) == 10) {
                array_shift($this->boxes);
            }
            $this->boxes[] = AABB::fromUser($user);
        }
    }

}