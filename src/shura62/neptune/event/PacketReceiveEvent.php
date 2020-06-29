<?php

declare(strict_types=1);

namespace shura62\neptune\event;

class PacketReceiveEvent extends NeptuneEvent {

    public function equals(int $other) : bool{
        return $this->packet->pid() == $other;
    }

}