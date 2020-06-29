<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace shura62\neptune\event;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\Player;

abstract class NeptuneEvent {

    protected $player;
    protected $packet;

    public function __construct(Player $player, DataPacket $packet) {
        $this->player = $player;
        $this->packet = $packet;
    }

    public function getPlayer() : Player{
        return $this->player;
    }

    public function getPacket() : DataPacket{
        return $this->packet;
    }

=======
<?php

declare(strict_types=1);

namespace shura62\neptune\event;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\Player;

abstract class NeptuneEvent {

    private $player;
    private $packet;
    
    public function __construct(Player $player, DataPacket $packet) {
        $this->player = $player;
        $this->packet = $packet;
    }
    
    public function getPlayer() : Player{
        return $this->player;
    }
    
    public function getPacket() : DataPacket{
        return $this->packet;
    }
    
>>>>>>> 41753135f6b613be18f0874b70ff0ada4d1e948d
}