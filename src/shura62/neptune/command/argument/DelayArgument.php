<?php

declare(strict_types=1);

namespace shura62\neptune\command\argument;

use pocketmine\utils\TextFormat;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\utils\ChatUtils;

class DelayArgument implements Argument {

    public function execute(User $user, array $args) {
        if (!isset($args[0])) {
            $user->getPlayer()->sendMessage(TextFormat::RED . "Usage: /neptune delay <delay>");
            return;
        }
        $delay = $args[0];
        if (!is_numeric($delay))
            $delay = 1;
        $user->flagDelay = $delay;
        $user->getPlayer()->sendMessage(ChatUtils::color(NeptunePlugin::getInstance()->getConfig()->getNested('lang.base-message-color')) . "Flag delay has been set to " . $delay . ".");
    }

}