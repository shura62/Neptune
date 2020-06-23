<?php

declare(strict_types=1);

namespace shura62\neptune\event;

use pocketmine\entity\Living;
use shura62\neptune\Neptune;
use shura62\neptune\utils\packet\api\Client;

class PacketReceiveEvent extends NeptuneEvent {

    public function equalsPacketType(string $type) : bool{
        return $this->getPacket()->getName() == $type
            && ($type !== Client::INVENTORY_TRANSACTION
                ? true
                : (property_exists($this->getPacket()->trData, 'entityRuntimeId')
                    && ($entity = Neptune::getInstance()->getServer()->findEntity($this->getPacket()->trData->entityRuntimeId)) instanceof Living));
    }
    
    
}