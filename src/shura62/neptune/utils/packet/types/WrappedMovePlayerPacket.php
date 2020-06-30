<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet\types;

use pocketmine\network\mcpe\protocol\DataPacket;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\WrappedPacket;

class WrappedMovePlayerPacket extends WrappedPacket {

    public $look, $pos;
    private $user;

    public function __construct(DataPacket $pk, User $user) {
        $this->user = $user;
        parent::__construct($pk);
    }

    protected function process() : void{
        $velocity = $this->user->velocity;
        $this->pos = $velocity->length() > 0;

        $curr = $this->user->position;
        $prev = $this->user->lastPosition;

        $this->look = $curr->getYaw() - $prev->getYaw() !== 0 || $curr->getPitch() - $prev->getPitch() !== 0;
    }

}