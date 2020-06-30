<?php

declare(strict_types=1);

namespace shura62\neptune\command\argument;

use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\utils\ChatUtils;

class AlertsArgument implements Argument {

    public function execute(User $user, array $args) {
        $color = ChatUtils::color(NeptunePlugin::getInstance()->getConfig()->getNested('lang.base-message-color'));

        if($user->alerts === null && $user->getPlayer()->hasPermission('neptune.alerts'))
            $user->alerts = true;

        if ($user->alerts) {
            $user->alerts = false;
            $user->getPlayer()->sendMessage($color . "You are no longer viewing anticheat alerts.");
        } else {
            $user->alerts = true;
            $user->getPlayer()->sendMessage($color . "You are now viewing anticheat alerts.");
        }
    }

}