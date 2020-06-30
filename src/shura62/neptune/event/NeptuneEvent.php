<?php

declare(strict_types=1);

namespace shura62\neptune\event;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\Player;

abstract class NeptuneEvent {

    protected $player;
    protected $packet;

    public function __construct(Player $player, DataPacket $packet) {
        $this->player = $player;
        $this->packet = $packet;
    }

    public function getPlayer() : Player{
        return $this->player;
    }

    public function getPacket() : DataPacket{
        return $this->packet;
    }

}