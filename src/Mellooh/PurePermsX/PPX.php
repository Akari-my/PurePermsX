<?php

namespace Mellooh\PurePermsX;

use Mellooh\PurePermsX\commands\PermCommand;
use Mellooh\PurePermsX\handler\PermissionHandler;
use Mellooh\PurePermsX\listener\EventListener;
use Mellooh\PurePermsX\manager\GroupManager;
use Mellooh\PurePermsX\manager\UserManager;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\plugin\PluginBase;

class PPX extends PluginBase {

    private static PPX $instance;

    public GroupManager $groupManager;
    public UserManager $userManager;
    private PermissionHandler $permissionHandler;

    public function onEnable(): void {
        self::$instance = $this;

        @mkdir($this->getDataFolder() . "groups/");
        @mkdir($this->getDataFolder() . "users/");

        $this->groupManager = new GroupManager($this);
        $this->userManager = new UserManager($this);
        $this->permissionHandler = new PermissionHandler();

        MessageManager::init($this);

        $this->getServer()->getCommandMap()->register("ppx", new PermCommand($this));
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        $this->getLogger()->info("§b
#  ______              ______                      __   __
#  | ___ \             | ___ \                     \ \ / /
#  | |_/ _   _ _ __ ___| |_/ ___ _ __ _ __ ___  ___ \ V / 
#  |  __| | | | '__/ _ |  __/ _ | '__| '_ ` _ \/ __|/   \ 
#  | |  | |_| | | |  __| | |  __| |  | | | | | \__ / /^\ \
#  \_|   \__,_|_|  \___\_|  \___|_|  |_| |_| |_|___\/   \/
#                                                         
# Activated
# by Mellooh                                                         
");

        if (!$this->getGroupManager()->groupExists("guest")) {
            $this->getGroupManager()->createGroup("guest");
            $this->getGroupManager()->addPermission("guest", "pocketmine.command.me");
            $this->getGroupManager()->addPermission("guest", "pocketmine.command.list");
            $this->getGroupManager()->addPermission("guest", "pocketmine.command.help");
            $this->getLogger()->info("§aDefault group 'guest' has been created with default permissions.");
        }
    }

    public static function getInstance(): PPX {
        return self::$instance;
    }

    public function getGroupManager(): GroupManager {
        return $this->groupManager;
    }

    public function getUserManager(): UserManager {
        return $this->userManager;
    }

    public function getPermissionHandler(): PermissionHandler {
        return $this->permissionHandler;
    }
}