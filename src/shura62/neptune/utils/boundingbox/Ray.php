<?php

declare(strict_types=1);

namespace shura62\neptune\utils\boundingbox;

use pocketmine\math\Vector3;

class Ray {

    private $origin, $direction;

    public function __construct(Vector3 $origin, Vector3 $direction) {
        $this->origin = $origin;
        $this->direction = $direction;
    }

    public function getOrigin() : Vector3{
        return $this->origin;
    }

    public function getDirection() : Vector3{
        return $this->direction;
    }

}