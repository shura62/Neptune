<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

interface Packets {

    const MOVE = ProtocolInfo::MOVE_PLAYER_PACKET;
    const INVENTORY_TRANSACTION = ProtocolInfo::INVENTORY_TRANSACTION_PACKET;
    const ANIMATE = ProtocolInfo::ANIMATE_PACKET;
    const ACTION = ProtocolInfo::PLAYER_ACTION_PACKET;
    const LOGIN = ProtocolInfo::LOGIN_PACKET;
    const INTERACT = ProtocolInfo::INTERACT_PACKET;
    const NETWORK_STACK_LATENCY = ProtocolInfo::NETWORK_STACK_LATENCY_PACKET;

}