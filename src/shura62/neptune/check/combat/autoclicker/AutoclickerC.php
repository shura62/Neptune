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

class AutoclickerC extends Check {

    private $samples = [];
    private $movements = 0;
    private $lastKurtosis = 0, $lastSkewness = 0, $lastDeviation = 0;
    
    public function __construct() {
        parent::__construct("Autoclicker", "Statistic", CheckType::COMBAT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::ANIMATE)) {
            $pk = new WrappedAnimatePacket($e->getPacket());
    
            if ($pk->swung) {
                $valid = $this->movements < 4 && !$user->digging;
                
                if ($valid) {
                    $this->samples[] = $this->movements;
                }
    
                if (count($this->samples) == 10) {
                    $deviation = MathUtils::getStandardDeviation($this->samples);
                    $skewness = MathUtils::getSkewness($this->samples);
                    $kurtosis = MathUtils::getKurtosis($this->samples);
        
                    if ($deviation == $this->lastDeviation && $skewness == $this->lastSkewness && $kurtosis == $this->lastKurtosis) {
                        if (++$this->vl > 2) {
                            $this->flag($user, "deviation= " . $deviation . ", skewness= " . $skewness . ", kurtosis= " . $kurtosis);
                        }
                    } else $this->vl = 0;
        
                    $this->lastDeviation = $deviation;
                    $this->lastKurtosis = $kurtosis;
                    $this->lastSkewness = $skewness;
                    $this->samples = [];
                }
            }
            
            $this->movements = 0;
        } elseif ($e->equals(Packets::NETWORK_STACK_LATENCY)) {
            ++$this->movements;
        }
    }
    
}