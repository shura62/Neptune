<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet\types;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\InteractPacket;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\utils\packet\WrappedPacket;

class WrappedInteractPacket extends WrappedPacket {

    public $target;
    public $pos;

    protected function process() : void{
        $pk = $this->packet;
        if($pk->action === InteractPacket::ACTION_MOUSEOVER) {
            $this->pos = new Vector3($pk->x, $pk->y, $pk->z);
            $this->target = NeptunePlugin::getInstance()->getServer()->findEntity($pk->target);
        }
    }

}