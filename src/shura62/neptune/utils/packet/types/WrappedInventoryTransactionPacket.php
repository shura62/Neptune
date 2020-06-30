<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet\types;

use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\utils\packet\WrappedPacket;

class WrappedInventoryTransactionPacket extends WrappedPacket {

    public $block;
    public $entity;

    protected function process() : void{
        $pk = $this->packet;
        $this->entity =
            ($pk->transactionType == InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY
            && $pk->trData->actionType == InventoryTransactionPacket::USE_ITEM_ON_ENTITY_ACTION_ATTACK)
                ? NeptunePlugin::getInstance()->getServer()->findEntity($pk->trData->entityRuntimeId)
                : null;
    }

}
