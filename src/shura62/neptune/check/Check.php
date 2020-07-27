<?php

declare(strict_types=1);

namespace shura62\neptune\check;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\scheduler\ClosureTask;
use shura62\neptune\event\PacketReceiveEvent;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\User;
use shura62\neptune\user\UserManager;
use shura62\neptune\utils\ChatUtils;
use shura62\neptune\utils\Timestamp;

abstract class Check {

    private $name, $type;
    private $violations;
    protected $enabled, $punishable, $dev;
    protected $vl = 0, $maxViolations, $category;

    public function __construct(string $name, string $type, int $category) {
        $this->name = $name;
        $this->type = $type;
        $this->category = $category;

        $this->enabled = NeptunePlugin::getInstance()->getConfig()->getNested('checks.detections.' . strtolower($this->getName()) . "." . strtolower($this->getType()) . ".enabled");
        $this->punishable = NeptunePlugin::getInstance()->getConfig()->getNested('checks.detections.' . strtolower($this->getName()) . "." . strtolower($this->getType()) . ".punishable");
        $this->maxViolations = NeptunePlugin::getInstance()->getConfig()->getNested('checks.detections.' . strtolower($this->getName()) . "." . strtolower($this->getType()) . ".max-vl");
    }

    protected function flag(User $user, string $information = "") : void{
        $this->violations[] = new Violation($information);

        $message = ChatUtils::color(str_replace("%prefix%", NeptunePlugin::getInstance()->getPrefix(), str_replace("%player%", $user->getPlayer()->getName(), str_replace("%check%", $this->getName(), str_replace("%checktype%", $this->getType(), str_replace("%vl%", count($this->violations), str_replace("%info%", $information, NeptunePlugin::getInstance()->getConfig()->getNested('lang.flag-format'))))))) . ($this->dev ? NeptunePlugin::getInstance()->getConfig()->getNested('lang.experimental-annotation') : ""));
        ChatUtils::informStaff($message, count($this->violations));

        if($this instanceof Cancellable) {
            switch ($this->getCategory()) {
                case CheckType::MOVEMENT:
                    if ($user->lastMoveFlag === null) {
                        $user->lastMoveFlag = new Timestamp();
                    } else $user->lastMoveFlag->reset();
                    break;
            }
        }

        if (count($this->violations) >= $this->maxViolations) {
            if ($this->punishable) {
                if (NeptunePlugin::getInstance()->getConfig()->getNested('checks.broadcast-punishments'))
                    $user->getPlayer()->getServer()->broadcastMessage(ChatUtils::color(str_replace("%prefix%", NeptunePlugin::getInstance()->getPrefix(), str_replace("%player%", $user->getPlayer()->getName(), str_replace("check", $this->getName(), str_replace("%checktype%", $this->getType(), str_replace("%newline%", "\n", NeptunePlugin::getInstance()->getConfig()->getNested('lang.broadcast-message'))))))));

                NeptunePlugin::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($user) : void{
                    NeptunePlugin::getInstance()->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace("%prefix%", NeptunePlugin::getInstance()->getPrefix(), str_replace("%player%", $user->getPlayer()->getName(), str_replace("%check%", $this->getName(), str_replace("%checktype%", $this->getType(), NeptunePlugin::getInstance()->getConfig()->getNested('checks.punish-command'))))));
                }), 10);

                UserManager::unregister($user->getPlayer());
                $this->violations = [];
            }
        }
        $this->vl = 0;
    }

    public abstract function onPacket(PacketReceiveEvent $e, User $user);
    
    public function getName() : string{
        return $this->name;
    }

    public function getType() : string{
        return $this->type;
    }

    public function getCategory() : int{
        return $this->category;
    }

    public function isEnabled() : bool{
        return $this->enabled;
    }
    
    public function canRunBeforeLogin() : bool{
        return false;
    }
    
    public function canRunAfterTeleport() : bool{
        return false;
    }
    
    public function setEnabled(bool $enable) : void{
        $this->enabled = $enable;
    }

}