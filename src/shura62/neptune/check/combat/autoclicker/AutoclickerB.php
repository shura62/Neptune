<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\autoclicker;

use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedAnimatePacket;

class AutoclickerB extends Check {

    private $ticks;

    public function __construct() {
        parent::__construct("Autoclicker", "Delay");
        $this->dev = true;
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        /*if ($e->equals(Packets::ANIMATE)) {
            $pk = new WrappedAnimatePacket($e->getPacket());

            if ($pk->swung && !$user->digging) {
                if ($this->ticks < 1) {
                    if(++$this->vl > 2)
                        $this->flag($user, "ticks= " . $this->ticks);
                } else $this->vl = 0;

                $this->ticks = 0;
            }
        } elseif ($e->equals(Packets::MOVE)) {
            ++$this->ticks;
        }*/
    }

}