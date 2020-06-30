<?php

declare(strict_types=1);

namespace shura62\neptune\check\player\timer;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\MathUtils;
use shura62\neptune\utils\packet\Packets;

class TimerA extends Check {

    private $lastDeviation;
    private $packetDeque;

    public function __construct() {
        parent::__construct("Timer", "Consistency");
        $this->dev = true;
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        /*if(!$e->equals(Packets::MOVE))
            return;
        $this->packetDeque[] = microtime(true) / 1000;

        if(count($this->packetDeque) == 50) {
            $deviation = MathUtils::getStandardDeviation($this->packetDeque);

            if($deviation <= 710 && (abs($deviation - $this->lastDeviation) < 20)) {
                if(++$this->vl > 4)
                    $this->flag($user, "deviation= " . $deviation);
            } else $this->vl = 0;

            $this->lastDeviation = $deviation;
            $this->packetDeque = [];
        }*/
    }

}