<?php

declare(strict_types=1);

namespace shura62\neptune\utils\boundingbox;

use pocketmine\math\Vector3;

class AABB {

    private $minX, $minY, $minZ;
    private $maxX, $maxY, $maxZ;

    public function __construct(float $minX, float $minY, float $minZ, float $maxX, float $maxY, float $maxZ) {
        $this->minX = $minX;
        $this->minY = $minY;
        $this->minZ = $minZ;
        $this->maxX = $maxX;
        $this->maxY = $maxY;
        $this->maxZ = $maxZ;
    }

    public function contains(Vector3 $pos) : bool{
        return $pos->getX() <= $this->maxX
                    && $pos->getY() <= $this->maxY
                    && $pos->getZ() <= $this->maxZ
                    && $pos->getX() >= $this->minX
                    && $pos->getY() >= $this->minY
                    && $pos->getZ() >= $this->minZ;
    }

    public function getMin() : Vector3{
        return new Vector3($this->minX, $this->minY, $this->minZ);
    }

    public function getMax() : Vector3{
        return new Vector3($this->maxX, $this->maxY, $this->maxZ);
    }

}