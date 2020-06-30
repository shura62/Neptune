<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use shura62\neptune\NeptunePlugin;

class Timestamp {

    private $start;

    public function __construct() {
        $this->reset();
    }

    public function getPassed() : int{
        return $this->getTick() - $this->start;
    }

    public function hasPassed(int $toPass) : bool{
        return $this->getPassed() > $toPass;
    }

    public function hasNotPassed(int $toPass) : bool{
        return $this->getPassed() < $toPass;
    }

    public function getTick() : int{
        return NeptunePlugin::getInstance()->getServer()->getTick();
    }

    public function reset() : void{
        $this->start = $this->getTick();
    }

}