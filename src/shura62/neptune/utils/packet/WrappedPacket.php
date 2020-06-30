<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet;

use pocketmine\network\mcpe\protocol\DataPacket;

abstract class WrappedPacket {

    protected $packet;

    public function __construct(DataPacket $pk) {
        $this->packet = $pk;
        $this->process();
    }

    protected abstract function process() : void;

}