<?php

declare(strict_types=1);

namespace shura62\neptune;

use pocketmine\plugin\PluginBase;
use shura62\neptune\command\NeptuneCommand;
use shura62\neptune\listener\ConnectionListener;
use shura62\neptune\listener\NetworkListener;
use shura62\neptune\listener\PocketMineListener;
use shura62\neptune\utils\ChatUtils;

class NeptunePlugin extends PluginBase {
    private static $instance;

    public function onEnable() : void{
        // Instance
        self::$instance = $this;
        // Listeners
        new ConnectionListener();
        new NetworkListener();
        new PocketMineListener();
        // Commands
        $this->getServer()->getCommandMap()->register($this->getName(), new NeptuneCommand());
        // Config
        $this->saveDefaultConfig();
    }

    public static function getInstance() : NeptunePlugin{
        return self::$instance;
    }

    public function getPrefix() : string{
        return ChatUtils::color($this->getConfig()->getNested('lang.prefix'));
    }

}