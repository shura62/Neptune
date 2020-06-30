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

}