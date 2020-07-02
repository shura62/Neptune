<?php

declare(strict_types=1);

namespace shura62\neptune\listener;

use pocketmine\entity\Human;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Player;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\UserManager;

class PocketMineListener implements Listener {

    public function __construct() {
        NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
    }

    /**
     * @priority HIGHEST
     * @param EntityDamageByEntityEvent $event
     */
    public function onEntityHit(EntityDamageByEntityEvent $event) : void{
        $entity = $event->getEntity();
        if($entity instanceof Player && $event->getDamager() instanceof Human && !$event->isCancelled()) {
            $user = UserManager::get($entity);
            if($user !== null)
                $user->lastKnockBack->reset();
        }
    }

    /**
     * @priority HIGHEST
     * @param EntityTeleportEvent $event
     */
    public function onTeleport(EntityTeleportEvent $event) : void{
        $entity = $event->getEntity();
        if($entity instanceof Player && !$event->isCancelled()) {
            $user = UserManager::get($entity);
            if($user !== null && ($user->lastMoveFlag === null || $user->lastMoveFlag->hasNotPassed(1)))
                $user->lastTeleport->reset();
        }
    }

    /**
     * @priority HIGHEST
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event) : void{
        if(!$event->isCancelled()) {
            $user = UserManager::get($event->getPlayer());
            if($user !== null)
                $user->lastBlockPlace->reset();
        }
    }

    /**
     * @priority LOWEST
     * @param PlayerMoveEvent $event
     */
    public function onMove(PlayerMoveEvent $event) : void{
        $user = UserManager::get($event->getPlayer());
        if($user !== null) {
            if($user->lastMoveFlag !== null && $user->lastMoveFlag->hasPassed(0) && $user->lastMoveFlag->hasNotPassed(2)) {
                if($user->lastGroundPosition !== null)
                    $event->getPlayer()->teleport($user->lastGroundPosition);
            }
        }
    }

}