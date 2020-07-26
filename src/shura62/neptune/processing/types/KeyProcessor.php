<?php

declare(strict_types=1);

namespace shura62\neptune\processing\types;

use pocketmine\block\BlockIds;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use shura62\neptune\processing\Processor;
use shura62\neptune\user\User;
use shura62\neptune\utils\MathUtils;

class KeyProcessor extends Processor {
    
    public const W = "W";
    public const A = "A";
    public const S = "S";
    public const D = "D";
    
    private $user;
    private $lastVelocity;
    private $keys = [self::W => false, self::A => false, self::S => false, self::D => false];
    
    public function __construct(User $user) {
        $this->user = $user;
    }
    
    public function process(DataPacket $packet) : void{
        if (!($packet instanceof MovePlayerPacket) || $this->user->position->level === null) {
            return;
        }
        $user = $this->user;
     
        $velocity = new Vector3(
            $user->position->getX() - $user->lastPosition->getX(),
            0,
            $user->position->getZ() - $user->lastPosition->getZ()
        );
        
        $deltaX = $velocity->getX();
        $deltaZ = $velocity->getZ();
        
        $friction = $user->position->getLevel()->getBlockAt(
                (int)floor($user->position->getX()),
                (int)floor($user->boundingBox->getMin()->getY() - 1),
                (int)floor($user->position->getZ()))
            ->getFrictionFactor();
        
        $prevVelocity = $this->lastVelocity !== null
            ? clone $this->lastVelocity
            : clone $velocity;
        
        if (abs($prevVelocity->getX() * $friction) < 0.005) {
            $prevVelocity->x = 0;
        }
        if (abs($prevVelocity->getZ() * $friction) < 0.005) {
            $prevVelocity->z = 0;
        }
        
        $deltaX /= $friction;
        $deltaZ /= $friction;
        $deltaX -= $prevVelocity->getX();
        $deltaZ -= $prevVelocity->getZ();
        
        $accelDir = new Vector3($deltaX, 0, $deltaZ);
        $yaw = MathUtils::getDirection($user->position->getYaw(), 0);
    
        $this->resetKeys();
        
        if ($accelDir->lengthSquared() >= 0.000001) {
            $vectorDir = $accelDir->cross($yaw)->dot(new Vector3(0, 1, 0)) >= 0;
            $angle = ($vectorDir ? 1 : -1) * MathUtils::angle($accelDir, $yaw);
            
            $deg = round(rad2deg($angle));
            
            if (abs($deg) == 0 || abs($deg) == 45) {
                // Forward
                $this->setPressing(self::W, true);
            } elseif (abs($deg) == 180 || abs($deg) == 135) {
                // Backwards
                $this->setPressing(self::S, true);
            }
            
            if ($deg == 90 || $deg == 135 || $deg == 45) {
                // Right
                $this->setPressing(self::D, true);
            } elseif ($deg == -90 || $deg == -135 || $deg == -45) {
                // Left
                $this->setPressing(self::A, true);
            }
        }
        
        $this->lastVelocity = $velocity;
    }
    
    public function setPressing(string $key, bool $value, bool $updateRelative = true) : void{
        $this->keys[$key] = $value;
        
        if ($updateRelative) {
            $other = self::getRelative($key);
            $this->keys[$other] = !$value;
        }
    }
    
    public static function getRelative(string $key) {
        switch ($key) {
            case self::W:
                return self::S;
            case self::S:
                return self::W;
            case self::A:
                return self::D;
            case self::D:
                return self::A;
        }
    }
    
    public function isPressing(int $min = -1, ...$keys) : bool{
        $flagged = 0;
        foreach ($keys as $key) {
            if (isset($this->keys[$key]) && $this->keys[$key]) {
                ++$flagged;
            }
        }
        
        if ($min == -1) {
            return $flagged == count($keys);
        } else {
            return $flagged >= $min;
        }
    }
    
    public function isNotPressing(...$keys) : bool{
        $flagged = 0;
        foreach ($keys as $key) {
            if (isset($this->keys[$key]) && !$this->keys[$key]) {
                ++$flagged;
            }
        }
    
        return $flagged == count($keys);
    }
    
    public function resetKeys() : void{
        foreach ($this->keys as $key => $pressing) {
            $this->setPressing($key, false, false);
        }
    }
    
}