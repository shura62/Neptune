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

class AutoclickerD extends Check {
    
    private $samples = [];
    private $movements = 0;
    
    public function __construct() {
        parent::__construct("Autoclicker", "StatisticB", CheckType::COMBAT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::ANIMATE)) {
            $pk = new WrappedAnimatePacket($e->getPacket());
    
            if ($pk->swung) {
                $valid = $this->movements < 4 && !$user->digging;
                if ($valid) {
                    $this->samples[] = $this->movements;
                }
    
                if (count($this->samples) == 15) {
                    $outlierPair = MathUtils::getOutliers($this->samples);
        
                    $skewness = MathUtils::getSkewness($this->samples);
                    $kurtosis = MathUtils::getKurtosis($this->samples);
                    $outliers = count($outlierPair->getX()) + count($outlierPair->getY());
        
                    if ($skewness < 0.75 && $kurtosis < 0 && $outliers < 2) {
                        $this->flag($user, "skewness= " . $skewness . ", kurtosis= " . $kurtosis . ", outliers= " . $outliers);
                    }
        
                    $this->samples = [];
                }
            }
            
            $this->movements = 0;
        } elseif ($e->equals(Packets::NETWORK_STACK_LATENCY)) {
            ++$this->movements;
        }
    }
    
}