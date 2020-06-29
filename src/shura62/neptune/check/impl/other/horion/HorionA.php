<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\other\horion;

use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\api\Client;

class HorionA extends Check {
    
    public function __construct() {
        parent::__construct("Horion", "A");
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        if ($user->clientMobile && !$user->serverMobile) {
            $this->flag($user, "detected EditionFaker");
        }
    }
    
}