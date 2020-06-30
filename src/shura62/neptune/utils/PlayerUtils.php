<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\entity\Effect;
use pocketmine\Player;
use shura62\neptune\user\User;

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

}