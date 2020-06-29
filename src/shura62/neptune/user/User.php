<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace shura62\neptune\user;

use pocketmine\Player;
use shura62\neptune\check\CheckManager;
use shura62\neptune\processing\types\DeviceProcessor;
use shura62\neptune\processing\types\MovementProcessor;
use shura62\neptune\processing\types\PacketProcessor;
use shura62\neptune\utils\Timestamp;

class User {

    private $player;

    public $boundingBox;
    public $position, $lastPosition;
    public $velocity, $lastVelocity;
    public $clientGround, $collidedGround, $alerts, $inventoryOpen, $digging, $desktop;
    public $movementProcessor, $packetProcessor, $deviceProcessor;
    public $flagDelay, $airTicks, $groundTicks, $iceTicks, $slimeTicks, $liquidTicks, $climbableTicks, $cobwebTicks, $blocksAboveTicks;
    public $lastTeleport, $lastKnockBack;
    public $checks = [];

    public function __construct(Player $player) {
        $this->player = $player;

        $this->movementProcessor = new MovementProcessor();
        $this->packetProcessor = new PacketProcessor();
        $this->deviceProcessor = new DeviceProcessor();

        $this->flagDelay = 1;

        $this->checks = new CheckManager();

        $this->lastTeleport = new Timestamp();
        $this->lastKnockBack = new Timestamp();
    }

    public function getPlayer() : Player{
        return $this->player;
    }

=======
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
    
>>>>>>> 41753135f6b613be18f0874b70ff0ada4d1e948d
}