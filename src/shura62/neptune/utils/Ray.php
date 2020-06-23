<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\math\Vector3;

class Ray {

    private $orig, $dir;
    
    public function __construct(Vector3 $origin, Vector3 $direction) {
        $this->orig = $origin;
        $this->dir = $direction;
    }
    
    public function getOrigin() : Vector3{
        return $this->orig;
    }
    
    public function getDirection() : Vector3{
        return $this->dir;
    }
    
}