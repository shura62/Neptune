<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet\types;

use pocketmine\network\mcpe\protocol\AnimatePacket;
use shura62\neptune\utils\packet\WrappedPacket;

class WrappedAnimatePacket extends WrappedPacket {

    public $swung;

    protected function process() : void{
        $this->swung = $this->packet->action == AnimatePacket::ACTION_SWING_ARM;
    }

}