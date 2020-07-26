<?php

declare(strict_types=1);

namespace shura62\neptune\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use shura62\neptune\check\player\spoof\SpoofA;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\user\UserManager;

class ConnectionListener implements Listener {

    public function __construct() {
        NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
    }
    
    /**
     * @priority LOW
     * @param DataPacketReceiveEvent $event
     */
    public function onLogin(DataPacketReceiveEvent $event) : void{
        $packet = $event->getPacket();
        
        if($packet instanceof LoginPacket) {
            $user = new User($event->getPlayer());
            UserManager::register($user);
            
            $user->online = false;
            $user->deviceProcessor->process($packet);
        }
    }
    
    public function onJoin(PlayerJoinEvent $event) : void{
        $user = UserManager::get($event->getPlayer());
        $user->online = true;
    }

    public function onQuit(PlayerQuitEvent $event) : void{
        UserManager::unregister($event->getPlayer());
    }

}