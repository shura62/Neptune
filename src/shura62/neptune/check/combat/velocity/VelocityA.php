<?php

declare(strict_types=1);

namespace shura62\neptune\check\combat\velocity;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\Player;
use shura62\neptune\check\Check;
use shura62\neptune\check\CheckType;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\user\UserManager;
use shura62\neptune\utils\packet\Packets;

// Credits to senpayeh for helping

class VelocityA extends Check implements Listener {

    /** @var Vector3[] */
    private $velocities = [];
    private $user;
    
    private $minVertical;
    private $minHorizontal;
    private $maxTicks = 0;
    
    public function __construct() {
        parent::__construct("Velocity", "Reducer", CheckType::COMBAT);
        NeptunePlugin::getInstance()->getServer()->getPluginManager()->registerEvents($this, NeptunePlugin::getInstance());
    }

    public function onPacket(PacketReceiveEvent $e, User $user) {
        if ($e->equals(Packets::MOVE)) {
            $this->user = $user;
            
            $vel = $user->velocity;
            $timestamp = $user->lastKnockBack;
            
            $maxTicks = $this->maxTicks;
            // Parse
            if ($timestamp !== null && $timestamp->hasNotPassed($maxTicks + 1)) {
                $this->velocities[] = $vel;
            } else {
                // Analyze
                if ($timestamp->hasNotPassed($maxTicks + 2)) {
                    if (count($this->velocities) == 0) {
                        return;
                    }
    
                    $verticals = [];
                    $horizontals = [];
                    foreach ($this->velocities as $vector) {
                        $verticals[] = $vector->getY();
                        $horizontals[] = hypot($vector->getX(), $vector->getZ());
                    }
    
                    $vertical = min($verticals);
                    $horizontal = min($horizontals);
                    
                    $diffX = abs($horizontal - $this->minHorizontal);
                    $diffY = abs($vertical - $this->minVertical);
                    
                    if ($diffX >= 0.2 || $diffY <= 0.1 && $user->blocksAboveTicks < 1) {
                        if(++$this->vl > 2 || $diffX >= 0.22) {
                            $this->flag($user, "diffX= " . $diffX . ", diffY= " . $diffY);
                        }
                    } else $this->vl = 0;
                    
                    $this->velocities = [];
                }
            }
        }
    }
    
    /**
     * @priority HIGHEST
     * @param EntityDamageByEntityEvent $event
     */
    public function onAttack(EntityDamageByEntityEvent $event) : void{
        $victim = $event->getEntity();
        $damager = $event->getDamager();
        if (!($victim instanceof Player)) {
            return;
        }
        $user = UserManager::get($victim);
        
        if ($user === $this->user) {
            $base = $event->getKnockBack();
            
            $diffX = $victim->getX() - $damager->getX();
            $diffZ = $victim->getZ() - $damager->getZ();
            $multiplier = 1 / hypot($diffX, $diffZ);
            
            $this->maxTicks = (int) ceil($victim->getPing() / 50) + 4;
            $velocity = $user->velocity;
            
            // We start from the maximum values
            $minVertical = $velocity->getY() / 2 + $base;
            $maxX = ($velocity->getX() / 2) + $diffX * $multiplier * $base;
            $maxZ = ($velocity->getZ() / 2) + $diffZ * $multiplier * $base;
            $minHorizontal = hypot($maxX, $maxZ);
            // Now we calculate the minimum vertical/horizontal velocity
            // We also use minecraft formulas to predict with reasonable accuracy the next velocity
            for ($i = 0; $i <= $this->maxTicks; $minVertical = ($minVertical - 0.08) * 0.98, $minHorizontal *= 0.91, ++$i) {
            }
            
            // Parse the values
            $this->minHorizontal = $minHorizontal;
            $this->minVertical = $minVertical;
        }
    }
    
}