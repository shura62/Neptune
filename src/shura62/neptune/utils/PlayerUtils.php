<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\block\Block;
use pocketmine\block\Liquid;
use pocketmine\entity\Effect;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\Player;
use shura62\neptune\user\User;
use shura62\neptune\utils\block\WrappedBlock;

class PlayerUtils {

    public static function getBaseMovementSpeed(User $user) : float{
        $p = $user->getPlayer();
        $max = $p->isSprinting()
        ? 0.29 // 5.612 m/s
        : 0.216; // 4.317 m/s
        $max += self::getPotionEffectLevel($p, Effect::SPEED) * 0.2;
        
        return $max;
    }
    
    public static function getPotionEffectLevel(Player $player, int $id) : int{
        if (($e = $player->getEffect($id)) !== null)
            return $e->getEffectLevel();
        return 0;
    }
    
    public static function hasBlocksAround(Location $loc) : bool{
        $one = $loc->subtract(1, 0, 1);
        $two = $loc->add(1, 1, 1);
        
        $minX = min($one->getFloorX(), $two->getFloorX());
        $minY = min($one->getFloorY(), $two->getFloorY());
        $minZ = min($one->getFloorZ(), $two->getFloorZ());
        $maxX = max($one->getFloorX(), $two->getFloorX());
        $maxY = max($one->getFloorY(), $two->getFloorY());
        $maxZ = max($one->getFloorZ(), $two->getFloorZ());
        
        for ($x = $minX; $x < $maxX; $x++) {
            for ($y = $minY; $y < $maxY; $y++) {
                for ($z = $minZ; $z < $maxZ; $z++) {
                    $pos = new Vector3($x, $y, $z);
                    $b = WrappedBlock::get($loc->level->getBlock($pos));
                    if ($b->isSolid())
                        return true;
                }
            }
        }
        return false;
    }
    
    public static function isInClimbable(Player $player) : bool{
        foreach ($player->getBlocksAround() as $b)
            if (in_array($b->getId(), [Block::LADDER, Block::VINES]))
                return true;
        return false;
    }
    
    public static function isInCobweb(Player $player) : bool{
        foreach ($player->getBlocksAround() as $b)
            if (in_array($b->getId(), [Block::COBWEB]))
                return true;
        return false;
    }
    
    public static function isInLiquid(Player $player) : bool{
        foreach ($player->getBlocksAround() as $b)
            if ($b instanceof Liquid)
                return true;
        return false;
    }
    
}