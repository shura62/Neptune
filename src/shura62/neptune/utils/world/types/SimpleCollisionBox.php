<?php

declare(strict_types=1);

namespace shura62\neptune\utils\world\types;

use pocketmine\block\Block;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use shura62\neptune\utils\world\CollisionBox;

class SimpleCollisionBox implements CollisionBox {
    
    public $minX, $minY, $minZ, $maxX, $maxY, $maxZ;
    
    public static function fromVectors(Vector3 $min, Vector3 $max) : SimpleCollisionBox{
        return new SimpleCollisionBox($min->getX(), $min->getY(), $min->getZ(), $max->getX(), $max->getY(), $max->getZ());
    }
    
    public static function fromVector(Vector3 $vec, float $width, float $height) : SimpleCollisionBox{
        $box = new SimpleCollisionBox($vec->getX(), $vec->getY(), $vec->getZ(), $vec->getX(), $vec->getY(), $vec->getZ());
        
        $box->expand($width / 2, 0, $width / 2);
        $box->maxY += $height;
        
        return $box;
    }
    
    public static function fromAABB(AxisAlignedBB $bb) : SimpleCollisionBox{
        return new SimpleCollisionBox($bb->minX, $bb->minY, $bb->minZ, $bb->maxX, $bb->maxY, $bb->maxZ);
    }
    
    public static function fromBlock(Block $block) : SimpleCollisionBox{
        return self::fromAABB($block->getBoundingBox());
    }
    
    public function __construct(float $minX, float $minY, float $minZ, float $maxX, float $maxY, float $maxZ) {
        if ($minX < $maxX) {
            $this->minX = $minX;
            $this->maxX = $maxX;
        } else {
            $this->minX = $maxX;
            $this->maxX = $minX;
        }
        if ($minY < $maxY) {
            $this->minY = $minY;
            $this->maxY = $maxY;
        } else {
            $this->minY = $maxY;
            $this->maxY = $minY;
        }
        if ($minZ < $maxZ) {
            $this->minZ = $minZ;
            $this->maxZ = $maxZ;
        } else {
            $this->minZ = $maxZ;
            $this->maxZ = $minZ;
        }
    }
    
    public function copy() : CollisionBox{
        return new SimpleCollisionBox($this->minX, $this->minY, $this->minZ, $this->maxX, $this->maxY, $this->maxZ);
    }
    
    public function offset(float $x, float $y, float $z) : CollisionBox{
        $this->minX += $x;
        $this->minY += $y;
        $this->minZ += $z;
        $this->maxX += $x;
        $this->maxY += $y;
        $this->maxZ += $z;
        return $this;
    }
    
    public function expand(float $x, float $y, float $z) : SimpleCollisionBox{
        $this->minX -= $x;
        $this->minY -= $y;
        $this->minZ -= $z;
        $this->maxX += $x;
        $this->maxY += $y;
        $this->maxZ += $z;
        return $this;
    }
    
    public function expandMax(float $x, float $y, float $z) : SimpleCollisionBox{
        $this->maxX += $x;
        $this->maxY += $y;
        $this->maxZ += $z;
        return $this;
    }
    
    public function expandAll(float $value) : SimpleCollisionBox{
        return $this->expand($value, $value, $value);
    }
    
    public function isCollided(CollisionBox $other) : bool{
        if ($other instanceof SimpleCollisionBox) {
            return $other->maxX >= $this->minX && $other->minX <= $this->maxX
                    && $other->maxY >= $this->minY && $other->minY <= $this->maxY
                    && $other->maxZ >= $this->minZ && $other->minZ <= $this->maxZ;
        } else {
            return $other->isCollided($this);
        }
    }
    
    public function downCast(array &$list) : void{
        $list[] = $this;
    }
    
    public function isNull() : bool{
        return false;
    }
    
}