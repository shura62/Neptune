<?php

declare(strict_types=1);

namespace shura62\neptune\listener;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\BatchPacket;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\Neptune;
use shura62\neptune\user\UserManager;

class NetworkListener implements Listener {
    
    private $plugin;
    
    public function __construct(Neptune $plugin) {
        $this->plugin = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }
    
    /**
     * @priority HIGHEST
     * @param DataPacketReceiveEvent $ev
     */
    public function onPacketReceive(DataPacketReceiveEvent $ev) : void{
        $pk = $ev->getPacket();
        $user = UserManager::getUser($ev->getPlayer());
        if ($user !== null && !$pk instanceof BatchPacket) {
            $p = $user->getPlayer();
            ++$user->tick;
            
            // Process packet
            Neptune::getInstance()->getMovementProcessor()->processMovement($pk, $user);
            Neptune::getInstance()->getDigProcessor()->processDig($pk, $user);
            
            foreach ($user->checks as $check) {
                if ($check->isEnabled()
                        && microtime(true) - $user->lastTeleport > 2
                        && $user->getPlugin()->getServer()->getTicksPerSecond() > 19) {
                    $e = new PacketReceiveEvent($p, $pk);
                    $check->onPacket($e, $user);
                }
            }
        }
    }
    
}