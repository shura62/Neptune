<?php

declare(strict_types=1);

namespace shura62\neptune\processor;

use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use shura62\neptune\user\User;
use shura62\neptune\utils\block\WrappedBlock;
use shura62\neptune\utils\entity\WrappedEntity;

class MovementProcessor {

    public function processMovement(DataPacket $pk, User $user) : void{
        if ($pk instanceof MovePlayerPacket) {
            $player = $user->getPlayer();
            $user->teleportTicks = 0;
            
            $p = $pk->position;
            $pos = new Location($p->getX(), $p->getY(), $p->getZ(), $pk->yaw, $pk->pitch, $player->getLevel());
            $lPos = $user->position ?? $pos;
            $user->position = $pos;
            $user->lastPosition = $lPos;
            
            $vel = new Vector3(abs($pos->getX() - $lPos->getX()), abs($pos->getY() - $lPos->getY()), abs($pos->getZ() - $lPos->getZ()));
            $lVel = $user->velocity;
            $user->velocity = $vel;
            $user->lastVelocity = $lVel ?? $vel;
            
            $user->boundingBox = WrappedEntity::get($player)->getCollisionBox();
            
            $serverGround = false;
            $offset = 0.3;
            for ($x = -$offset; $x <= $offset; $x += $offset) {
                for ($z = -$offset; $z <= $offset; $z += $offset) {
                    if ($user->boundingBox->intersectsWith(WrappedBlock::get($player->getLevel()->getBlock($player->add($x, ($player->isSneaking() ? -0.4601 : -0.5001), $z)))->getBoundingBox()->expand(0.05, 0.01, 0.05))) {
                        $serverGround = true;
                    }
                }
            }
            $user->nearGround = $serverGround;
            $user->clientGround = $pk->onGround;
            
            if ($serverGround) {
                ++$user->groundTicks;
                $user->airTicks = 0;
            } else {
                ++$user->airTicks;
                $user->groundTicks = 0;
            }
        }
    }

}