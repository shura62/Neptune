<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\scaffold;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;

class ScaffoldB extends Check {

    public function __construct() {
        parent::__construct("Scaffold", "Delay", CheckType::MOVEMENT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
    }

}