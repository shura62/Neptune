<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace shura62\neptune\listener;

use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\UserManager;

class PocketMineListener implements Listener {

    public function __construct() {
        NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
    }

    public function onEntityHit(EntityDamageByEntityEvent $event) : void{
        $entity = $event->getEntity();
        if($entity instanceof Player && $event->getDamager() instanceof Human) {
            $user = UserManager::get($entity);
            if($user !== null)
                $user->lastKnockBack->reset();
        }
    }

=======
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
    
>>>>>>> 41753135f6b613be18f0874b70ff0ada4d1e948d
}