<?php

declare(strict_types=1);

namespace shura62\neptune\listener;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\Player;
use shura62\neptune\Neptune;
use shura62\neptune\user\UserManager;

class PocketMineListener implements Listener {
    
    private $plugin;
    
    public function __construct(Neptune $plugin) {
        $this->plugin = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }
    
    public function onTeleport(EntityTeleportEvent $e) : void{
        if ($e->getEntity() instanceof Player) {
            $user = UserManager::getUser($e->getEntity());
            if ($user !== null)
                $user->lastTeleport = microtime(true);
        }
    }
    
    public function onLevelChange(EntityLevelChangeEvent $e) : void {
        if ($e->getEntity() instanceof Player) {
            $user = UserManager::getUser($e->getEntity());
            if ($user !== null)
                $user->lastTeleport = microtime(true);
        }
    }
    
    public function onRespawn(PlayerRespawnEvent $e) : void {
        $user = UserManager::getUser($e->getPlayer());
        if ($user !== null)
            $user->lastTeleport = microtime(true);
    }
    
    public function onKnockBack(EntityDamageByEntityEvent $e) : void{
        if ($e->getEntity() instanceof Player) {
            $user = UserManager::getUser($e->getEntity());
            if ($user !== null)
                $user->lastKnockBack = microtime(true);
        }
    }
    
}