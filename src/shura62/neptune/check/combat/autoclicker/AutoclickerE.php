<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\autoclicker;

use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\packet\types\WrappedAnimatePacket;

class AutoclickerE extends Check {

    private $movements = 0, $clicks = 0;
    
    public function __construct() {
        parent::__construct("Autoclicker", "Tick", CheckType::COMBAT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::ANIMATE)) {
            $pk = new WrappedAnimatePacket($e->getPacket());
    
            if ($pk->swung) {
                $valid = $this->movements < 100 && !$user->digging;
    
                if ($valid) {
                    ++$this->clicks;
                }
    
                if ($this->movements == 20) {
                    $flag = $this->clicks > 20;
        
                    if ($flag) {
                        $this->flag($user, "clicks= " . $this->clicks);
                    }
                }
            }
    
            $this->movements = 0;
        } elseif ($e->equals(Packets::NETWORK_STACK_LATENCY)) {
            ++$this->movements;
        }
    }
    
}