<?php

declare(strict_types=1);

namespace shura62\neptune\check\player\nofall;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\Timestamp;

class NoFallA extends Check {

    private $ticks;

    public function __construct() {
        parent::__construct("NoFall", "GroundSpoof", CheckType::PLAYER);
        $this->ticks = new Timestamp();
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if(!$e->equals(Packets::MOVE))
            return;
        if($this->ticks->getPassed() == 0)
            return;
        $this->ticks->reset();

        $client = $user->clientGround;
        $server = $user->collidedGround;

        if($client && !$server
                && $user->liquidTicks <= 0
                && $user->climbableTicks <= 0
                && $user->cobwebTicks <= 0
                && $user->lastBlockPlace->hasPassed(20)
                && ($user->lastMoveFlag === null || $user->lastMoveFlag->hasPassed(20)
                && $user->position->getY() > 0)
                && !$e->getPlayer()->getAllowFlight()) {
            if(++$this->vl > 8)
                $this->flag($user, "client= " . ($client ? "true" : "false") . ", server= " . ($server ? "true" : "false"));
        } else $this->vl = 0;
    }

}