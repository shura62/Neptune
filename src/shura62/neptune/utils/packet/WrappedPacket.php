<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet;

use pocketmine\network\mcpe\protocol\DataPacket;

abstract class WrappedPacket {

    protected $packet;

    public function __construct(DataPacket $pk) {
        $this->packet = $pk;
        $this->process();
    }

    protected abstract function process() : void;

=======
<?php

declare(strict_types=1);

namespace shura62\neptune\utils\packet;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\Player;

abstract class WrappedPacket {

    private $player;
    private $packet;
    
    public function __construct(Player $player, DataPacket $pk) {
        $this->player = $player;
        $this->packet = $pk;
        $this->process();
    }
    
    protected abstract function process();
    
    public function getPlayer() : Player{
        return $this->player;
    }
    
    public function getPacket() : DataPacket{
        return $this->packet;
    }
    
>>>>>>> 41753135f6b613be18f0874b70ff0ada4d1e948d
}