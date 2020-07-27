<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\invalid;

use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\processing\types\KeyProcessor;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class InvalidC extends Check {

    public function __construct() {
        parent::__construct("Invalid", "Sprint", CheckType::MOVEMENT);
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::ACTION)) {
            $sprinting = $e->getPacket()->action === PlayerActionPacket::ACTION_START_SPRINT;
            $flag = $user->keyProcessor->isPressing(1, KeyProcessor::A, KeyProcessor::D)
                && $user->keyProcessor->isNotPressing(KeyProcessor::W, KeyProcessor::S);
           
            if ($sprinting && $flag) {
                $this->flag($user);
            }
        }
    }
    
}