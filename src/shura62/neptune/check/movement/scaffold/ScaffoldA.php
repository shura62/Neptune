<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\scaffold;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\user\UserManager;
use shura62\neptune\utils\boundingbox\AABB;
use shura62\neptune\utils\boundingbox\Ray;
use shura62\neptune\utils\packet\Packets;

class ScaffoldA extends Check implements Listener {

    private $position;
    private $user;

    public function __construct() {
        parent::__construct("Scaffold", "Angle");
        NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
        $this->dev = true;
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if($e->equals(Packets::INVENTORY_TRANSACTION)) {
            if($e->getPacket()->transactionType === InventoryTransactionPacket::TYPE_USE_ITEM) {
                $this->user = $user;
                $this->position = $user->position;
            }
        }
    }

    /**
     * @priority HIGHEST
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event) : void{
        if($event->isCancelled())
            return;
        $user = UserManager::get($event->getPlayer());
        if($user === null || $user !== $this->user)
            return;

        $pos = $this->position;

        $under = $pos->level->getBlock($pos->subtract(0, $user->getPlayer()->getEyeHeight() + 0.5001));

        $replaced = $event->getBlockReplaced();
        $bb = AABB::fromBlock($under);

        $difY = $pos->getY() - $replaced->getY();
        $difXZ = hypot($pos->getX() - $replaced->getX(), $pos->getZ() - $replaced->getZ());

        if ($bb !== null && abs($difY) >= 2.6 && abs($difY) < 3 && $difXZ < 2) {
            $collision =
                $bb->grow($pos->x - $replaced->x, $pos->y - $replaced->y, $pos->z - $replaced->z)
                    ->grow(2, 0, 2)
                    ->collidesRay(Ray::from($user), 1, 5);

            // TODO: find out why -1 is returned when player is pointing west direction
            $outside = $collision == -1
                && $user->getPlayer()->getDirection() !== 1;

            if($collision >= 2.5 || $outside) {
                if(++$this->vl > 1)
                    $this->flag($user);
            } else $this->vl = 0;
        }
    }

}