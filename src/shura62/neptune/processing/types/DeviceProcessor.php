<?php

declare(strict_types=1);

namespace shura62\neptune\processing\types;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use shura62\neptune\processing\Processor;
use shura62\neptune\user\User;

// Credits to TheNewHEROBRINE

class DeviceProcessor extends Processor {

    public function process(DataPacket $packet, User $user) : void{
        if(!($packet instanceof LoginPacket))
            return;
        try {
            $data = $packet->chainData;
            $part = explode(".", $data['chain'][2]);

            $jwt = json_decode(base64_decode($part[1]), true);
            $id = (int) $jwt['extraData']['titleId'];
        } catch (\Exception $e) {
            $id = -1;
        }
        $user->desktop = !in_array(base64_encode((string) $id), ["MTczOTk0NzQzNg==", "MTgxMDkyNDI0Nw=="]);
    }

}