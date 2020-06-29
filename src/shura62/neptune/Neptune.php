<?php

declare(strict_types=1);

namespace shura62\neptune;

use pocketmine\plugin\PluginBase;
use shura62\neptune\command\NeptuneCommand;
use shura62\neptune\listener\NeptuneListener;
use shura62\neptune\listener\NetworkListener;
use shura62\neptune\listener\PocketMineListener;
use shura62\neptune\processor\DigProcessor;
use shura62\neptune\processor\MovementProcessor;
use shura62\neptune\utils\ChatUtils;
use shura62\neptune\utils\MiscUtils;

class Neptune extends PluginBase {
    
    private static $instance;
    private $movementProcessor, $digProcessor;
    
    public static function getInstance() : Neptune{
        return self::$instance;
    }
    
    public function onLoad() {
        self::$instance = $this;
        $this->getServer()->getCommandMap()->register($this->getName(), new NeptuneCommand());
        
        $this->movementProcessor = new MovementProcessor();
        $this->digProcessor = new DigProcessor();
        
        MiscUtils::init();
    }
    
    public function onEnable() {
        new NeptuneListener($this);
        new NetworkListener($this);
        new PocketMineListener($this);
    }
    
    public function getMovementProcessor() : MovementProcessor{
        return $this->movementProcessor;
    }
    
    public function getDigProcessor() : DigProcessor{
        return $this->digProcessor;
    }
    
    public function getPrefix() : string{
        return ChatUtils::color($this->getConfig()->getNested('lang.prefix'));
    }
    
}
