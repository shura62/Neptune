<?php

declare(strict_types=1);

namespace shura62\neptune\user;

use pocketmine\Player;
use shura62\neptune\check\api\CheckManager;
use shura62\neptune\Neptune;

class User {
    
    private $player;
    public $boundingBox;
    public $lastTeleport, $lastKnockBack, $airTicks, $groundTicks;
    public $alerts, $flagDelay, $serverMobile, $clientMobile, $digging, $clientGround, $nearGround;
    public $position, $lastPosition, $velocity, $lastVelocity;
    public $tick;
    public $checks;
    private $plugin;

    public function __construct(Neptune $plugin, Player $player, int $serverId, int $clientId) {
        $this->player = $player;
        $this->plugin = $plugin;
        
        $this->serverMobile = in_array($serverId, [1810924247, 1739947436]);
        $this->clientMobile = in_array($clientId, [1, 2]);
        
        $this->checks = (new CheckManager())->loadChecks();
        
        $this->lastTeleport = microtime(true);
        $this->alerts = $player->hasPermission('neptune.alerts');
    }
    
    public function getPlayer() : Player{
        return $this->player;
    }
    
    public function getPlayerName() : string{
        return $this->player->getLowerCaseName();
    }
    
    public function getPlugin() : Neptune{
        return $this->plugin;
    }
    
}