<?php

declare(strict_types=1);

namespace shura62\neptune\command\argument;

use pocketmine\utils\TextFormat;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;

class DisableArgument implements Argument {
    
    public function execute(User $user, array $args) {
        $player = $user->getPlayer();
        if (!isset($args[0]) || !isset($args[1])) {
            $player->sendMessage(TextFormat::RED . "Usage: /neptune disable <checkName> <checkType>");
            return;
        }
        
        $name = $args[0];
        $type = $args[1];
        
        $check = null;
        foreach ($user->checks->get() as $class) {
            $cname = strtolower($class->getName());
            $ctype = strtolower($class->getType());
            
            if ($cname == strtolower($name) && $ctype == strtolower($type)) {
                $check = $class;
            }
        }
        
        if ($check !== null) {
            if ($check->isEnabled()) {
                $player->sendMessage(TextFormat::WHITE . $check->getName() . " <" . $check->getType() . "> " . TextFormat::GREEN . "is now disabled.");
                
                $check->setEnabled(false);
                NeptunePlugin::getInstance()->getConfig()->setNested('checks.detections.' . strtolower($name) . "." . strtolower($type) . ".enabled", false);
            } else {
                $player->sendMessage(TextFormat::RED . "This check is already disabled.");
            }
        } else {
            $player->sendMessage(TextFormat::RED . "The specified check doesn't exist.");
        }
    }
    
}