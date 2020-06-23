<?php

declare(strict_types=1);

namespace shura62\neptune\user;

use pocketmine\Player;

class UserManager {

    private static $users = [];
    
    public static function register(User $user) : void{
        self::$users[$user->getPlayerName()] = $user;
    }
    
    public static function unregister(User $user) : void{
        unset(self::$users[$user->getPlayerName()]);
    }
    
    public static function getUser(Player $p) : ?User{
        return self::$users[$p->getLowerCaseName()]
            ?? null;
    }
    
    public static function getUsers() : array{
        return self::$users;
    }

}