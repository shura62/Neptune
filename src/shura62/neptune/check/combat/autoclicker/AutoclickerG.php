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

class AutoclickerG extends Check {
    
    private $movements = 0;
    private $samples = [];
    
    public function __construct() {
        parent::__construct("Autoclicker", "Limit", CheckType::COMBAT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::ANIMATE)) {
            $pk = new WrappedAnimatePacket($e->getPacket());
            
            if ($pk->swung) {
                $valid = $this->movements < 5 && !$user->digging;
    
                if ($valid) {
                    $this->samples[] = $this->movements;
                }
    
                if (count($this->samples) == 15) {
                    $outlierPair = MathUtils::getOutliers($this->samples);
        
                    $skewness = MathUtils::getSkewness($this->samples);
                    $kurtosis = MathUtils::getKurtosis($this->samples);
                    $outliers = count($outlierPair->getX()) + count($outlierPair->getY());
        
                    if ($skewness < 0.035 && $kurtosis < 0.1 && $outliers < 2) {
                        $this->flag($user, "outliers= " . $outliers . ", kurtosis= " . $kurtosis . ", outliers= " . $outliers);
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