<?php

declare(strict_types=1);

namespace shura62\neptune\check\player\spoof;

use pocketmine\network\mcpe\protocol\types\DeviceOS;
use shura62\neptune\check\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\packet\Packets;

class SpoofA extends Check {

    private $spoofed;

    public function __construct() {
        parent::__construct("Spoof", "Edition");
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if($e->equals(Packets::LOGIN)) {
            $data = $e->getPacket()->clientData;
            $os = $data['DeviceOS'] ?? DeviceOS::UNKNOWN;

            $desktop = !in_array($os, [DeviceOS::ANDROID, DeviceOS::IOS]);

            $this->spoofed = !$desktop && $user->desktop;
        } elseif($e->equals(Packets::MOVE)) {
            if($this->spoofed)
                $this->flag($user, "edition faked= " . ($this->spoofed ? "true" : "false"));
        }
    }

}