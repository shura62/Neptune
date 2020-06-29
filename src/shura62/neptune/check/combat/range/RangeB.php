<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\range;

use pocketmine\entity\Entity;
use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedInteractPacket;

class RangeB extends Check {

    public function __construct() {
        parent::__construct("Range", "Packet");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        /*if (!$e->equals(Packets::INTERACT))
            return;
        $pk = new WrappedInteractPacket($e->getPacket());
        $target = $pk->target;

        if($target !== null) {
            $dist = $user->position->subtract(0, $user->position->y)->distance($pk->pos->subtract(0, $pk->pos->y)) - 0.3;
            $threshold = $user->getPlayer()->isCreative() ? 6 : 3;

            if($dist >= $threshold) {
                $this->flag($user, "distance= " . $dist);
            }
        }*/
    }

}