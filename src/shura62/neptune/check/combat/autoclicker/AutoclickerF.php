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

class AutoclickerF extends Check {
    
    private $movements = 0;
    private $samples = [];
    
    public function __construct() {
        parent::__construct("Autoclicker", "Duplicates", CheckType::COMBAT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::ANIMATE)) {
            $pk = new WrappedAnimatePacket($e->getPacket());
    
            if ($pk->swung) {
                $valid = $user->cps > 6 && $this->movements < 5 && !$user->digging;
    
                if ($valid) {
                    $this->samples[] = $this->movements;
                }
    
                if (count($this->samples) == 20) {
                    $outlierPair = MathUtils::getOutliers($this->samples);
        
                    $outliers = count($outlierPair->getX()) + count($outlierPair->getY());
                    $duplicates = count($this->samples) - count(array_unique($this->samples));
        
                    if ($outliers > 0 && $outliers < 2 && $duplicates > 17) {
                        $this->flag($user, "outliers= " . $outliers . ", duplicates= " . $duplicates);
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