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

    private $exemptedPlayers;
    
    public function __construct() {
        NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
        $this->exemptedPlayers = NeptunePlugin::getInstance()->getConfig()->getNested("exempted-players");
    }
    
    /**
     * @priority HIGHEST
     * @param DataPacketReceiveEvent $event
     */
    public function onPacket(DataPacketReceiveEvent $event) : void{
        $player = $event->getPlayer();
        $packet = $event->getPacket();
        if(!($packet instanceof BatchPacket)) {
            $user = UserManager::get($player);
            if($user !== null) {
                $user->movementProcessor->process($packet);
                $user->packetProcessor->process($packet);
                $user->clickProcessor->process($packet);
                $user->keyProcessor->process($packet);
                
                $exempted = $this->exemptedPlayers;
                
                if(!in_array($player->getName(), $exempted)
                        && $player->getServer()->getTicksPerSecond() >= 19
                        && $player->getY() <= -1) {
                    foreach($user->checks->get() as $check) {
                        if($check->isEnabled()) {
                           if ($user->lastTeleport !== null && $user->lastTeleport->hasNotPassed(100) && !$check->canRunAfterTeleport()) {
                               continue;
                           }
                            $check->onPacket(new PacketReceiveEvent($player, $packet), $user);
                        }
                    }
                    $user->movementProcessor->postProcess($packet);
                }
            }
        }
    }

}
