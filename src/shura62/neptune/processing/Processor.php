<?php

declare(strict_types=1);

namespace shura62\neptune\processing;

use pocketmine\network\mcpe\protocol\DataPacket;
use shura62\neptune\user\User;

abstract class Processor {

    public abstract function process(DataPacket $packet) : void;

}