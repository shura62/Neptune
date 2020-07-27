<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\block\Block;
use pocketmine\level\particle\FlameParticle;
use shura62\neptune\utils\world\types\SimpleCollisionBox;

class BlockUtils {

    public static function isClimbable(Block $block) : bool{
        return $block->getId() === Block::VINES || $block->getId() === Block::LADDER;
    }

    public static function isFencing(Block $block) : bool{
        return $block->getId() === Block::FENCE
                || $block->getId() === Block::NETHER_BRICK_FENCE
                || $block->getId() === Block::ACACIA_FENCE_GATE
                || $block->getId() === Block::BIRCH_FENCE_GATE
                || $block->getId() === Block::DARK_OAK_FENCE_GATE
                || $block->getId() === Block::JUNGLE_FENCE_GATE
                || $block->getId() === Block::SPRUCE_FENCE_GATE
                || $block->getId() === Block::OAK_FENCE_GATE
                || $block->getId() === Block::COBBLESTONE_WALL;
    }

    public static function isLiquid(Block $block) : bool{
        return $block->getId() === Block::WATER
                || $block->getId() === Block::FLOWING_WATER
                || $block->getId() === Block::LAVA
                || $block->getId() === Block::FLOWING_LAVA;
    }

}