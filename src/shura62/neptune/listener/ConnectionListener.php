<?php

declare(strict_types=1);

namespace shura62\neptune\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\user\UserManager;

class ConnectionListener implements Listener {

    public function __construct() {
        NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
    }

    public function onLogin(DataPacketReceiveEvent $event) : void{
        $packet = $event->getPacket();
        if($packet instanceof LoginPacket) {
            $user = new User($event->getPlayer());
            UserManager::register($user);

            $user->deviceProcessor->process($packet, $user);
        }
    }

    public function onQuit(PlayerQuitEvent $event) : void{
        UserManager::unregister($event->getPlayer());
    }

}