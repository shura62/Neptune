<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;

class AABB {
    
    private $max;
    private $min;
    
    public static function fromPocketMine(AxisAlignedBB $bb) : AABB{
        return new AABB(new Vector3($bb->minX, $bb->minY, $bb->minZ), new Vector3($bb->maxX, $bb->max, $bb->maxZ));
    }
    
    public function __construct(Vector3 $min, Vector3 $max) {
        $this->max = $max;
        $this->min = $min;
    }
    
    public function expand(float $x, float $y, float $z) : AABB{
        $compliment = new Vector3($x, $y, $z);
        $this->min = $this->min->subtract($compliment);
        $this->max = $this->max->add($compliment);
        return $this;
    }
    
    public function translate(Vector3 $vector3) : AABB{
        $this->min = $this->min->add($vector3);
        $this->max = $this->max->add($vector3);
        return $this;
    }
    
    public function modify(float $minX = 0, float $minY = 0, float $minZ = 0, float $maxX = 0, float $maxY = 0, float $maxZ = 0) : AABB{
        return new AABB($this->min->add($minX, $minY, $minZ), $this->max->add($maxX, $maxY, $maxZ));
    }
    
    public function intersectsWith(AABB $box) : bool{
        $epsilon = 0.00001;
        $min = $this->getMin();
        $otherMin = $box->getMin();
        $max = $this->getMax();
        $otherMax = $box->getMax();
        
        if ($otherMax->getX() - $min->getX() > $epsilon and $max->getX() - $otherMin->getX() > $epsilon) {
            if ($otherMax->getY() - $min->getY() > $epsilon and $max->getY() - $otherMin->getY() > $epsilon) {
                return $otherMax->getZ() - $min->getZ() > $epsilon and $max->getZ() - $otherMin->getZ() > $epsilon;
            }
        }
        return false;
    }
    
    public function getMax() : Vector3{
        return $this->max;
    }
    
    public function getMin() : Vector3{
        return $this->min;
    }
    
}