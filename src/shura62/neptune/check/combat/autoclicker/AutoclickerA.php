<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\autoclicker;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\MathUtils;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedAnimatePacket;

class AutoclickerA extends Check {

    private $ticks;
    private $hits = [];
    private $lastDeviation;

    public function __construct() {
        parent::__construct("Autoclicker", "Consistency", CheckType::COMBAT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if($e->equals(Packets::ANIMATE)) {
            $pk = new WrappedAnimatePacket($e->getPacket());

            if ($pk->swung) {
                if (!$user->digging)
                    $this->hits[] = $this->ticks * 50;
                if(count($this->hits) >= 10) {
                    $deviation = MathUtils::getStandardDeviation($this->hits);
                    $lastDeviation = $this->lastDeviation;
                    $this->lastDeviation = $deviation;

                    $diff = abs($deviation - $lastDeviation);

                    if($diff < 10) {
                        if(++$this->vl > 4)
                            $this->flag($user, "deviation= " . $deviation . ", lastDeviation= " . $lastDeviation);
                    } else $this->vl-= $this->vl > 0 ? 1 : 0;

                    $this->hits = [];
                }
            }
        } elseif($e->equals(Packets::MOVE)) {
            ++$this->ticks;
        }
    }

}