<?php

declare(strict_types=1);

namespace shura62\neptune\processing\types;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use shura62\neptune\processing\Processor;
use shura62\neptune\user\User;

class PacketProcessor extends Processor {

    public function process(DataPacket $packet, User $user) : void{
        switch($packet->pid()) {
            case ProtocolInfo::INTERACT_PACKET:
                if($packet->action === InteractPacket::ACTION_OPEN_INVENTORY)
                    $user->inventoryOpen = true;
                break;
            case ProtocolInfo::CONTAINER_CLOSE_PACKET:
                $user->inventoryOpen = false;
                break;
            case ProtocolInfo::PLAYER_ACTION_PACKET:
                if($packet->action === PlayerActionPacket::ACTION_ABORT_BREAK
                        || $packet->action === PlayerActionPacket::ACTION_STOP_BREAK)
                    $user->digging = false;
                if($packet->action === PlayerActionPacket::ACTION_START_BREAK
                    || $packet->action === PlayerActionPacket::ACTION_CONTINUE_BREAK)
                    $user->digging = true;
                break;
        }
    }

}