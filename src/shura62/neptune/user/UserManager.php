<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace shura62\neptune\user;

use pocketmine\Player;

class UserManager {

    private static $users = [];

    public static function get(Player $player) : ?User{
        return self::$users[spl_object_hash($player)] ?? null;
    }

    public static function register(User $user) : void{
        self::$users[spl_object_hash($user->getPlayer())] = $user;
    }

    public static function unregister(Player $player) : void{
        if(self::get($player) !== null) {
            unset(self::$users[spl_object_hash($player)]);
        }
    }

    public static function getAll() : array{
        return self::$users;
    }

=======
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

>>>>>>> 41753135f6b613be18f0874b70ff0ada4d1e948d
}