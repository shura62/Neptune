<?php

declare(strict_types=1);

namespace shura62\neptune\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use shura62\neptune\Neptune;
use shura62\neptune\user\User;
use shura62\neptune\user\UserManager;

class NeptuneListener implements Listener {
    
    private $plugin;
    private $pendingIds = [];
    
    public function __construct(Neptune $plugin) {
        $this->plugin = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }
    
    /**
     * @priority LOWEST
     * @param DataPacketReceiveEvent $e
     */
    public function onPacket(DataPacketReceiveEvent $e) : void{
        // Credits to TheNewHEROBRINE
        $pk = $e->getPacket();
        if ($pk instanceof LoginPacket) {
            try {
                $data = $pk->chainData;
                $part = explode(".", $data['chain'][2]);
    
                $jwt = json_decode(base64_decode($part[1]), true);
                $id = (int) $jwt['extraData']['titleId'];
            } catch (\Exception $exception) {
                $id = -1;
            } finally {
                $os = (isset($pk->clientData['DeviceOS']) ? (int) $pk->clientData['DeviceOS'] : -1);
                $this->pendingIds[spl_object_hash($e->getPlayer())] = [$id, $os];
            }
        }
    }
    
    public function onLogin(PlayerLoginEvent $e) : void{
        $p = $e->getPlayer();
        $ids = $this->pendingIds[spl_object_hash($e->getPlayer())] ?? [];
        UserManager::register(new User($this->plugin, $p, $ids[0] ?? -1, $ids[1] ?? -1));
    }
    
    public function onDisconnect(PlayerQuitEvent $e) : void{
        $p = $e->getPlayer();
        $user = UserManager::getUser($p);
        if ($user !== null)
            UserManager::unregister($user);
    }
    
}