<?php

declare(strict_types=1);

namespace shura62\neptune\utils\boundingbox;

use pocketmine\math\Vector3;
use shura62\neptune\user\User;

class Ray {

    private $origin, $direction;

    public static function from(User $user) : Ray{
        return new Ray(
            $user->getPlayer()->add(0, $user->getPlayer()->getEyeHeight()), $user->getPlayer()->getDirectionVector());
    }

    public function __construct(Vector3 $origin, Vector3 $direction) {
        $this->origin = $origin;
        $this->direction = $direction;
    }

    public function origin(int $i) : float{
        return [$this->origin->getX(), $this->origin->getY(), $this->origin->getZ()][$i] ?? 0.001;
    }

    public function direction(int $i) : float{
        return [$this->direction->getX(), $this->direction->getY(), $this->direction->getZ()][$i] ?? 0.001;
    }

    public function getOrigin() : Vector3{
        return $this->origin;
    }

    public function getDirection() : Vector3{
        return $this->direction;
    }

}