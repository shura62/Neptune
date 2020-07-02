<?php

declare(strict_types=1);

namespace shura62\neptune\check\movement\noslow;

use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class NoSlowB extends Check {

    public function __construct() {
        parent::__construct("NoSlow", "Bow", CheckType::MOVEMENT);
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equals(Packets::INVENTORY_TRANSACTION))
            return;
        $pk = $e->getPacket();

        if ($pk->transactionType === InventoryTransactionPacket::TYPE_RELEASE_ITEM
                && $pk->trData->actionType === InventoryTransactionPacket::RELEASE_ITEM_ACTION_RELEASE) {
            if($user->sprintingTicks > 5
                    && $user->getPlayer()->getInventory()->getItemInHand()->getId() === ItemIds::BOW)
                $this->flag($user, "ticks= " . $user->sprintingTicks);
        }
    }

}