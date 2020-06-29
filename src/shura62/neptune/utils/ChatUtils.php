<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\utils\TextFormat;
use shura62\neptune\user\UserManager;

class ChatUtils {

    public static function color(string $in) : string{
        return TextFormat::colorize($in);
    }

    public static function informStaff(string $in, float $vl) : void{
        foreach (UserManager::getAll() as $user) {
            if($user->getPlayer()->isOnline()) {
                if ($user->alerts === null && $user->getPlayer()->hasPermission('neptune.alerts'))
                    $user->alerts = true;
                if ($user->alerts && ($vl % ($user->flagDelay ?: 1)) == 0) {
                    $user->getPlayer()->sendMessage($in);
                }
            }
        }
    }

=======
<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\utils\TextFormat;
use shura62\neptune\user\UserManager;

class ChatUtils {

    public static function color(string $in) : string{
        return TextFormat::colorize($in);
    }
    
    public static function informStaff(string $in, float $vl) : void{
        foreach (UserManager::getUsers() as $user) {
            if ($user->alerts && ($vl % ($user->flagDelay > 0 ? $user->flagDelay : 1)) == 0) {
                $user->getPlayer()->sendMessage($in);
            }
        }
    }

>>>>>>> 41753135f6b613be18f0874b70ff0ada4d1e948d
}