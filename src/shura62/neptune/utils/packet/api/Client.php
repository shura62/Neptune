<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet\api;

interface Client {

    const MOVE = "MovePlayerPacket";
    const INVENTORY_TRANSACTION = "InventoryTransactionPacket";
    const ANIMATE = "AnimatePacket";
    const LOGIN = "LoginPacket";
    const PLAY_STATUS = "PlayStatusPacket";
    
}