<?php

declare(strict_types=1);

namespace shura62\neptune\check\impl\movement\phase;

use pocketmine\block\Block;
use shura62\neptune\check\api\Check;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\user\User;
use shura62\neptune\utils\block\WrappedBlock;
use shura62\neptune\utils\MiscUtils;
use shura62\neptune\utils\packet\api\Client;

class PhaseA extends Check {

    private const IGNORED_BLOCKS = [
        Block::SAND, Block::GRAVEL, Block::ANVIL
    ];
    
    public function __construct() {
        parent::__construct("Phase", "A");
        $this->dev = true;
    }
    
    public function onPacket(PacketReceiveEvent $e, User $user) {
        if (!$e->equalsPacketType(Client::MOVE))
            return;
        $blocks = [MiscUtils::getBlock($user->position), MiscUtils::getBlock($user->position->add(0, 1.5001), $user->position->level)];
        
        $collided = 0;
        try {
            foreach ($blocks as $block) {
                if ($block !== null && !in_array($block->getId(), self::IGNORED_BLOCKS)) {
                    $b = WrappedBlock::get($block);
                    if ($b->isSolid() && $b->intersectsWith($user->boundingBox))
                        $collided++;
                }
            }
    
            if ($collided > 0
                && !$user->getPlayer()->isCreative()) {
                if (++$this->vl > 2)
                    $this->flag($user, "collided=" . $collided);
            } else $this->vl = 0;
            
        } catch (\Exception $e) {
        }
    }
    
}