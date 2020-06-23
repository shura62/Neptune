<?php

declare(strict_types=1);

namespace shura62\neptune\utils\entity;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use shura62\neptune\utils\AABB;
use shura62\neptune\utils\MiscUtils;

class WrappedEntity {

    private $entity;
    private $collisionBox;
    
    public static function get(Entity $e) : WrappedEntity{
        return new WrappedEntity($e);
    }
    
    public function __construct(Entity $entity) {
        $this->entity = $entity;
        $bb = $entity->getBoundingBox();
        $min = new Vector3(0, 0, 0);
        $max = new Vector3(0, 0, 0);
        if ($bb !== null) {
            $min = new Vector3($bb->minX, $bb->minY, $bb->minZ);
            $max = new Vector3($bb->maxX, $bb->maxY, $bb->maxZ);
        }
        $this->collisionBox = new AABB($min, $max);
    }
    
    public function getCollisionBox(Vector3 $pos = null) : AABB{
        if ($pos === null)
            return $this->collisionBox;
        $diff = $pos->subtract($this->entity);
        $this->collisionBox = $this->collisionBox->translate($diff);
        return $this->collisionBox;
    }
    
    public function getHitbox() : AABB{
        $hitbox = $this->collisionBox;
        $sizes = MiscUtils::$entityDimensions[$this->entity::NETWORK_ID]
            ?? new Vector3(0, 0, 0);
        $hitbox->expand($sizes->getX(), $sizes->getY(), $sizes->getZ());
        return $hitbox;
    }
    
}