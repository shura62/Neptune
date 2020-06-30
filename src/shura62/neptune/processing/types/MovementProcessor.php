<?php

declare(strict_types=1);

namespace shura62\neptune\processing\types;

use pocketmine\block\Block;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use shura62\neptune\processing\Processor;
use shura62\neptune\user\User;
use shura62\neptune\utils\BlockUtils;
use shura62\neptune\utils\boundingbox\BoundingBox;

class MovementProcessor extends Processor {

    public function process(DataPacket $packet, User $user) : void{
        if(!($packet instanceof MovePlayerPacket))
            return;
        $client = $packet->position;

        if($user->position !== null) {
            $user->lastPosition = $user->position;
            $user->position = new Location($client->x, $client->y, $client->z, $packet->yaw, $packet->pitch, $user->getPlayer()->level);

            $user->lastVelocity = $user->velocity;
            $user->velocity = new Vector3(
                abs($client->getX() - $user->lastPosition->getX()),
                $client->getY() - $user->lastPosition->getY(),
                abs($client->getZ() - $user->lastPosition->getZ()),
            );

            $user->boundingBox = BoundingBox::from($user->getPlayer()->getBoundingBox());

            $user->clientGround = $packet->onGround;
            $user->collidedGround = count(
                    $user->boundingBox->subtract(0, 0.6)
                        ->getCollidingBlocks($user->getPlayer()->getLevel(), true)) > 0;

            if ($user->collidedGround) {
                ++$user->groundTicks;
                $user->airTicks = 0;
            } else {
                ++$user->airTicks;
                $user->groundTicks = 0;
            }

            $around = $user->getPlayer()->getBlocksAround();
            // Climbable blocks
            $user->climbableTicks += count(array_filter($around, function (Block $b) : bool{
                return BlockUtils::isClimbable($b);
            })) > 0 ? 1 : -$user->climbableTicks;
            // Liquids
            $user->liquidTicks += count(array_filter($around, function (Block $b) : bool{
                return BlockUtils::isLiquid($b);
            })) > 0 ? 1 : -$user->liquidTicks;
            // Cobwebs
            $user->cobwebTicks += count(array_filter($around, function (Block $b) : bool{
                return $b->getId() === Block::COBWEB;
            })) > 0 ? 1 : -$user->cobwebTicks;
            // Blocks above
            $user->blocksAboveTicks += count($user->boundingBox->add(0,0, 0, 0, 2)
                ->getCollidingBlocks($user->getPlayer()->getLevel())) > 0
                ? 1 : -$user->blocksAboveTicks;
        } else {
            $user->lastPosition = $user->position = new Location($client->x, $client->y, $client->y, $packet->yaw, $packet->pitch);;
            $user->lastVelocity = $user->velocity = new Vector3();
        }
    }

}