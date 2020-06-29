<?php

declare(strict_types=1);

namespace shura62\neptune\utils\boundingbox;

use pocketmine\math\Vector3;
use shura62\neptune\user\User;

class AABB {

    private $minX, $minY, $minZ;
    private $maxX, $maxY, $maxZ;

    public static function from(User $user) : AABB{
        $pos = $user->position;
        return new AABB(
            $pos->x - 0.3, $pos->y, $pos->z - 0.3,
            $pos->x + 0.3, $pos->y + 1.8, $pos->z + 0.3
        );
    }

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

    public function min(int $i) : float{
        return [$this->minX, $this->minY, $this->minZ][$i] ?? 0;
    }

    public function max(int $i) : float{
        return [$this->maxX, $this->maxY, $this->maxZ][$i] ?? 0;
    }

    // Credits to Technio
    public function collidesRay(Ray $ray, float $tmin, float $tmax) : float{
        for($i = 0; $i < 3; ++$i) {
            $d = 1 / $ray->direction($i);
            $t0 = ($this->min($i) - $ray->origin($i)) * $d;
            $t1 = ($this->max($i) - $ray->origin($i)) * $d;
            if($d < 0) {
                $t = $t0;
                $t0 = $t1;
                $t1 = $t;
            }
            $tmin = $t0 > $tmin ? $t0 : $tmin;
            $tmax = $t1 < $tmax ? $t1 : $tmax;
            if($tmax <= $tmin)
                return -1;
        }
        return $tmin;
    }

    public function getMin() : Vector3{
        return new Vector3($this->minX, $this->minY, $this->minZ);
    }

    public function getMax() : Vector3{
        return new Vector3($this->maxX, $this->maxY, $this->maxZ);
    }

}