<?php

declare(strict_types=1);

namespace shura62\neptune\processing\types;

use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use shura62\neptune\processing\Processor;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\types\WrappedAnimatePacket;

class ClickProcessor extends Processor {

    private $user;
    private $clicks = [];
    
    public function __construct(User $user) {
        $this->user = $user;
    }
    
    public function process(DataPacket $packet) : void{
        if (!($packet instanceof LevelSoundEventPacket)) {
            return;
        }
        
        $user = $this->user;
        $valid = !$user->digging && $packet->sound == LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE; //&& (new W($packet))->swung;
        
        if (!$valid) {
            return;
        }
        
        $now = microtime(true);
        if (count($this->clicks) == 100) {
            array_pop($this->clicks);
        }
        array_unshift($this->clicks, $now);
        
        $validClicks = array_filter($this->clicks, function (float $timestamp) use ($now) : bool{
            return $now - $timestamp <= 1;
        });
        
        $this->user->cps = count($validClicks);
    }
    
}