<?php

declare(strict_types=1);

namespace shura62\neptune\utils\world;

use shura62\neptune\utils\world\types\SimpleCollisionBox;

interface CollisionBox {

    public function isCollided(CollisionBox $other) : bool;
    
    public function copy() : CollisionBox;
    
    public function offset(float $x, float $y, float $z) : CollisionBox;
    
    /**
     * @param SimpleCollisionBox[] $list
     * @return void
     */
    public function downCast(array &$list) : void;
    
    public function isNull() : bool;
    
}