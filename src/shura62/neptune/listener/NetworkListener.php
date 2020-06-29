<<<<<<< HEAD
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

=======
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
    
>>>>>>> 41753135f6b613be18f0874b70ff0ada4d1e948d
}