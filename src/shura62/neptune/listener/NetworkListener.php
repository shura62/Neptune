<?php

declare(strict_types=1);

namespace shura62\neptune\listener;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\BatchPacket;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\UserManager;

class NetworkListener implements Listener {

    public function __construct() {
        NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
    }

    public function onPacket(DataPacketReceiveEvent $event) : void{
        $player = $event->getPlayer();
        $packet = $event->getPacket();
        if(!($packet instanceof BatchPacket)) {
            $user = UserManager::get($player);
            if($user !== null) {
                $user->movementProcessor->process($packet, $user);
                $user->packetProcessor->process($packet, $user);

                $exempted = NeptunePlugin::getInstance()->getConfig()->getNested("exempted-players");

                if($user->position !== null && !in_array($user->getPlayer()->getName(), $exempted))
                    foreach($user->checks->get() as $check) {
                        if($check->isEnabled()) {
                            $check->onPacket(new PacketReceiveEvent($player, $packet), $user);
                    }
                }
            }
        }
    }

}