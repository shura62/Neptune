<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet\impl;

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\Player;
use shura62\neptune\utils\packet\WrappedPacket;

class WrappedInventoryTransactionPacket extends WrappedPacket {
    
    private $entity;
    
    public function __construct(Player $player, InventoryTransactionPacket $pk) {
        parent::__construct($player, $pk);
    }
    
    protected function process() {
        $pk = $this->getPacket();
        $this->entity = $pk->transactionType == InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY
            && $pk->trData->actionType == InventoryTransactionPacket::USE_ITEM_ON_ENTITY_ACTION_ATTACK
            ? $this->getPlayer()->getServer()->findEntity($pk->trData->entityRuntimeId)
            : null;
    }
    
    public function getEntity() : ?Entity{
        return $this->entity;
    }
    
}