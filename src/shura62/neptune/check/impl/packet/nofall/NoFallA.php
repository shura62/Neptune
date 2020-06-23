<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\packet\nofall;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;

class NoFallA extends Check {

    // TODO: False flags on lag
    
    public function __construct() {
        parent::__construct("NoFall", "A");
        $this->dev = true;
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $client = $e->getPacket()->onGround;
        $server = $user->nearGround;
        
        /*if ($client !== $server && $user->airTicks > 2) {
            if (++$this->vl > 5)
                $this->flag($user, "client= " . ($client ? "true" : "false") . ", server= " . ($server ? "true" : "false"));
        } else $this->vl = 0;*/
    }
    
}