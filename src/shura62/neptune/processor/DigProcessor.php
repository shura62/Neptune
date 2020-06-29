<?php

declare(strict_types=1);

namespace shura62\neptune\processor;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use shura62\neptune\user\User;

class DigProcessor {

    public function processDig(DataPacket $pk, User $user) : void{
        if ($pk instanceof PlayerActionPacket) {
            $action = $pk->action;
            switch ($action) {
                case PlayerActionPacket::ACTION_START_BREAK:
                case PlayerActionPacket::ACTION_CONTINUE_BREAK:
                    $user->digging = true;
                    break;
                case PlayerActionPacket::ACTION_STOP_BREAK:
                case PlayerActionPacket::ACTION_ABORT_BREAK:
                    $user->digging = false;
            }
        }
    }
    
}