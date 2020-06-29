<?php

declare(strict_types=1);

namespace shura62\neptune\check\player\nofall;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class NoFallA extends Check {

    public function __construct() {
        parent::__construct("NoFall", "GroundSpoof");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        $client = $user->clientGround;
        $server = $user->collidedGround;

        if($client !== $server
                && $user->liquidTicks <= 0
                && $user->climbableTicks <= 0
                && $user->cobwebTicks <= 0
                && !$e->getPlayer()->isSpectator()) {
            if(++$this->vl > 8)
                $this->flag($user, "client= " . ($client ? "true" : "false") . ", server= " . ($server ? "true" : "false"));
        } else $this->vl = 0;
    }

}