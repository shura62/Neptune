<?php

declare(strict_types=1);

namespace shura62\neptune\processing\types;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use shura62\neptune\processing\Processor;
use shura62\neptune\user\User;

// Credits to TheNewHEROBRINE

class DeviceProcessor extends Processor {

    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function process(DataPacket $packet) : void{
        if(!($packet instanceof LoginPacket))
            return;
        $user = $this->user;
        try {
            $data = $packet->chainData;
            $part = explode(".", $data['chain'][2]);

            $jwt = json_decode(base64_decode($part[1]), true);
            $id = (int) $jwt['extraData']['titleId'];
        } catch (\Exception $e) {
            $id = -1;
        }
        
        $clientData = $packet->clientData;
        $os = isset($clientData['DeviceOS']) ? $clientData['DeviceOS'] : 0;
        
        $user->clientDesktop = !in_array($os, [DeviceOS::ANDROID, DeviceOS::IOS]);
        $user->desktop = !in_array($id, [1739947436, 1810924247]);
    }

}