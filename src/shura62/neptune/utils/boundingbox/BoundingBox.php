<?php

declare(strict_types=1);

namespace shura62\neptune\utils\boundingbox;

use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;

class BoundingBox {

    private $min, $max;

    public static function from(AxisAlignedBB $aabb) : BoundingBox{
        return new BoundingBox(
            new Vector3($aabb->minX, $aabb->minY, $aabb->minZ),
            new Vector3($aabb->maxX, $aabb->maxY, $aabb->maxZ));
    }

    public function __construct(Vector3 $min, Vector3 $max) {
        $this->min = $min;
        $this->max = $max;
    }

    public function add(float $minX = 0, float $minY = 0, float $minZ = 0, float $maxX = 0, float $maxY = 0, float $maxZ = 0) : BoundingBox{
        return new BoundingBox(
            new Vector3($this->min->getX() + $minX, $this->min->getY() + $minY, $this->min->getZ() + $minZ),
            new Vector3($this->max->getX() + $maxX, $this->max->getY() + $maxY, $this->max->getZ() + $maxZ));
    }

    public function subtract(float $minX = 0, float $minY = 0, float $minZ = 0, float $maxX = 0, float $maxY = 0, float $maxZ = 0) : BoundingBox{
        return $this->add(-$minX, -$minY, -$minZ, -$maxX, -$maxY, -$maxZ);
    }

    public function collides(BoundingBox $other) : bool{
        return $other->getMax()->getX() >= $this->getMin()->getX()
                        && $other->getMin()->getX() <= $this->getMax()->getX()
                        && $other->getMax()->getY() >= $this->getMin()->getY()
                        && $other->getMin()->getY() <= $this->getMax()->getY()
                        && $other->getMax()->getZ() >= $this->getMin()->getZ()
                        && $other->getMin()->getZ() <= $this->getMax()->getZ();
    }

    public function getCollidingBlocks(?Level $level, bool $checkBoxes = false) : array{
        $blocksAround = [];

        if($level !== null) {
            $inset = 0.001;

            $minX = (int) floor($this->min->x + $inset);
            $minY = (int) floor($this->min->y + $inset);
            $minZ = (int) floor($this->min->z + $inset);
            $maxX = (int) floor($this->max->x - $inset);
            $maxY = (int) floor($this->max->y - $inset);
            $maxZ = (int) floor($this->max->z - $inset);

            for ($z = $minZ; $z <= $maxZ; ++$z) {
                for ($x = $minX; $x <= $maxX; ++$x) {
                    for ($y = $minY; $y <= $maxY; ++$y) {
                        $block = $level->getBlockAt($x, $y, $z);
                        if($checkBoxes) {
                            foreach ($block->getCollisionBoxes() as $box) {
                                if($this->collides(BoundingBox::from($box))) {
                                    $blocksAround[] = $block;
                                    break;
                                }
                            }
                        } else {
                            $blocksAround[] = $block;
                        }
                    }
                }
            }
        }

        return $blocksAround;
    }

    public function getHorizontallyCollidedBlocks(?Level $level, float $height) : array{
        $blocksAround = [];

        if($level !== null) {
            $inset = 0.001;

            $minX = (int)floor($this->min->x + $inset);
            $minZ = (int)floor($this->min->z + $inset);
            $maxX = (int)floor($this->max->x - $inset);
            $maxZ = (int)floor($this->max->z - $inset);

            for ($z = $minZ; $z <= $maxZ; ++$z) {
                for ($x = $minX; $x <= $maxX; ++$x) {
                    $block = $level->getBlockAt($x, (int) floor($height), $z);
                    $box = BoundingBox::from($block->getBoundingBox());
                    if($this->collides($box))
                        $blocksAround[] = $block;
                }
            }
        }

        return $blocksAround;
    }

    public function getMin() : Vector3{
        return $this->min;
    }

    public function getMax() : Vector3{
        return $this->max;
    }

}