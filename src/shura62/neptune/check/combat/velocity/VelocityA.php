<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\velocity;

use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\Listener;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;
use shura62\neptune\utils\Timestamp;

class VelocityA extends Check implements Listener {

    private $entity;
    private $velY, $lastVelocity;

    public function __construct() {
        parent::__construct("Velocity", "Vertical", CheckType::COMBAT);
        //NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        /*if(!$e->equals(Packets::MOVE))
            return;
        if($this->entity === $user->getPlayer()
                && $this->lastVelocity->hasNotPassed(1)
                && $user->velocity->getY() <= $this->velY * 0.99
                //&& $user->blocksAboveTicks <= 0
                && $user->liquidTicks <= 0
                && $user->cobwebTicks <= 0) {
            $user->getPlayer()->sendMessage("vel= " . $this->velY . "; user= " . $user->velocity->y );
            if(++$this->vl > 0)
                $this->flag($user, "vertical= " . $this->velY);
        } else $this->vl = 0;*/
    }

    public function onVelocity(EntityMotionEvent $event) : void{
        $this->velY = $event->getVector()->getY();
        $this->entity = $event->getEntity();

        if($this->lastVelocity !== null) {
            $this->lastVelocity->reset();
        } else $this->lastVelocity = new Timestamp();
    }

}