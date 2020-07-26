<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\range;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\Player;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\user\UserManager;
use shura62\neptune\utils\boundingbox\AABB;
use shura62\neptune\utils\boundingbox\Ray;
use shura62\neptune\utils\MathUtils;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedInventoryTransactionPacket;
use shura62\neptune\utils\Pair;

class RangeA extends Check implements Listener {
    
    private $boxes = [];
    private $lastTarget;
    
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
           
                $from = $user->position;
                
                $ray = new Ray($from->add(0, $user->getPlayer()->getEyeHeight()), MathUtils::getDirection($from->getYaw(), $from->getPitch()));
                $collided = [];
                
                $tick = $user->getPlayer()->getServer()->getTick();
                $ping = ceil($user->getPlayer()->getPing() / 50);
                
                foreach ($this->boxes as $pair) {
                    if (abs($tick - $pair->getX() - $ping) < 4) {
                        $dist = $pair->getY()->collidesRay($ray, 0, 10);
                        
                        if ($dist != -1) {
                            $collided[] = $dist;
                        }
                    }
                }
                
                if (count($collided) == 0) {
                    return;
                }
                $distance = min($collided);
                
                if ($distance > 3) {
                    if (++$this->vl > 3) {
                        $this->flag($user, "dist= " . $distance);
                    }
                } else $this->vl = 0;
            }
        }
    }
    
    public function onPocketminePacket(DataPacketReceiveEvent $event) : void{
        $pk = $event->getPacket();
        if (get_class($pk) == MovePlayerPacket::class) {
            $player = $event->getPlayer();
            $user = UserManager::get($player);
            
            if ($user === $this->lastTarget && $user !== null) {
                if (count($this->boxes) == 10) {
                    array_shift($this->boxes);
                }
                $this->boxes[] = new Pair($player->getServer()->getTick(), AABB::fromUser($user));
            }
        }
    }
    
}