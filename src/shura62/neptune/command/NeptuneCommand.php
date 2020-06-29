<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace shura62\neptune\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use shura62\neptune\command\argument\AlertsArgument;
use shura62\neptune\command\argument\Argument;
use shura62\neptune\command\argument\DelayArgument;
use shura62\neptune\NeptunePlugin;
use shura62\neptune\user\UserManager;
use shura62\neptune\utils\ChatUtils;

class NeptuneCommand extends Command implements PluginIdentifiableCommand {

    private $arguments = [];

    public function __construct() {
        parent::__construct("neptune", "Neptune anti-cheat main command");

        $this->registerArgument(new AlertsArgument(), "alerts");
        $this->registerArgument(new DelayArgument(), "delay");

        $this->setPermission('neptune.view-command');
    }

    private function registerArgument(Argument $arg, string $name) : void{
        $this->arguments[$name] = $arg;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "You can use this command in-game only.");
            return false;
        }
        if (!isset($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Please specify an argument.");
            return false;
        }
        $arg = strtolower($args[0]);
        if (!isset($this->arguments[$arg])) {
            $sender->sendMessage(TextFormat::RED . "No argument found with the name '" . $arg . "'");
            return false;
        }
        $config = NeptunePlugin::getInstance()->getConfig();
        if (!$sender->hasPermission("neptune." . $arg)) {
            $sender->sendMessage(ChatUtils::color($config->getNested('lang.base-message-color') . $config->getNested('no-permission')));
            return false;
        }
        $command = $this->arguments[$arg];
        array_shift($args);
        $command->execute(UserManager::get($sender), $args);
    }

    public function getPlugin() : Plugin{
        return NeptunePlugin::getInstance();
    }

=======
<?php

declare(strict_types=1);

namespace shura62\neptune\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use shura62\neptune\command\argument\AlertsArgument;
use shura62\neptune\command\argument\Argument;
use shura62\neptune\command\argument\DelayArgument;
use shura62\neptune\Neptune;
use shura62\neptune\user\UserManager;

class NeptuneCommand extends Command implements PluginIdentifiableCommand {

    private $arguments = [];
    
    public function __construct() {
        parent::__construct("neptune", "Neptune anti-cheat main command");
        
        $this->registerArgument(new AlertsArgument(), "alerts");
        $this->registerArgument(new DelayArgument(), "delay");
    }
    
    private function registerArgument(Argument $arg, string $name) : void{
        $this->arguments[$name] = $arg;
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "You can use this command in-game only.");
            return false;
        }
        if (!isset($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Please specify an argument.");
            return false;
        }
        $arg = strtolower($args[0]);
        if (!isset($this->arguments[$arg])) {
            $sender->sendMessage(TextFormat::RED . "No argument found with the name '" . $arg . "'");
            return false;
        }
        if (!$sender->hasPermission("neptune." . $arg)) {
            $sender->sendMessage(Neptune::getInstance()->getConfig()->getNested('lang.base-message-color'));
            return false;
        }
        $command = $this->arguments[$arg];
        array_shift($args);
        $command->execute(UserManager::getUser($sender), $args);
    }
    
    public function getPlugin() : Plugin{
        return Neptune::getInstance();
    }
    
>>>>>>> 41753135f6b613be18f0874b70ff0ada4d1e948d
}