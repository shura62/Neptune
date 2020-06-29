<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

class MiscUtils {

    public static $entityDimensions;
    
    public static function init() : void{
        self::$entityDimensions = [
            Entity::WOLF => new Vector3(0.31, 0.8, 0.31),
            Entity::SHEEP => new Vector3(0.45, 1.3, 0.45),
            Entity::COW => new Vector3(0.45, 1.3, 0.45),
            Entity::PIG => new Vector3(0.45, 0.9, 0.45),
            Entity::MOOSHROOM => new Vector3(0.45, 1.3, 0.45),
            Entity::WITCH => new Vector3(0.31, 1.95, 0.31),
            Entity::BLAZE => new Vector3(0.31, 1.8, 0.31),
            Entity::PLAYER => new Vector3(0.3, 1.8, 0.3),
            Entity::VILLAGER => new Vector3(0.31, 1.8, 0.31),
            Entity::CREEPER => new Vector3(0.31, 1.8, 0.31),
            Entity::SKELETON => new Vector3(0.31, 1.8, 0.31),
            Entity::ZOMBIE => new Vector3(0.31, 1.8, 0.31),
            Entity::SNOW_GOLEM => new Vector3(0.35, 1.9, 0.35),
            Entity::HORSE => new Vector3(0.7, 1.6, 0.7),
            Entity::ENDER_DRAGON => new Vector3(1.5, 1.5, 1.5),
            Entity::CHICKEN => new Vector3(0.2, 0.7, 0.2),
            Entity::OCELOT => new Vector3(0.31, 0.7, 0.31),
            Entity::SPIDER => new Vector3(0.7, 0.9, 0.7),
            Entity::WITHER => new Vector3(0.45, 3.5, 0.45),
            Entity::IRON_GOLEM => new Vector3(0.7, 2.9, 0.7),
            Entity::GHAST => new Vector3(2, 4, 2)
        ];
    }
    
    public static function getBlock($pos, Level $level = null) : ?Block{
        $level = isset($level) ? $level : $pos->level;
        if ($level !== null)
            return $level->getBlock($pos);
        return null;
    }
    
}