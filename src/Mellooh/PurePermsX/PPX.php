<?php

/**
 *      _____  _______   __
 *     |  __ \|  __ \ \ / /
 *     | |__) | |__) \ V /
 *     |  ___/|  ___/ > <
 *     | |    | |    / . \
 *     |_|    |_|   /_/ \_\
 *
 * * This program is free plugin: you can redistribute it and/or modify
 * * * it under the terms of the GNU Lesser General Public License as published by
 * * * the Free plugin Foundation, either version 3 of the License, or
 * * * (at your option) any later version.
 * * *
 * * * @author Mellooh
 * * * @link https://github.com/Akari-my
 * *
 */

namespace Mellooh\PurePermsX;

use Mellooh\PurePermsX\api\PurePermsX;
use Mellooh\PurePermsX\commands\PermCommand;
use Mellooh\PurePermsX\handler\PermissionHandler;
use Mellooh\PurePermsX\listener\EventListener;
use Mellooh\PurePermsX\manager\GroupManager;
use Mellooh\PurePermsX\manager\UserManager;
use Mellooh\PurePermsX\utils\MessageManager;
use Mellooh\PurePermsX\utils\UpdateChecker;
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
        $this->permissionHandler = new PermissionHandler($this);

        PurePermsX::init($this);
        MessageManager::init($this);
        UpdateChecker::check($this);

        $this->getServer()->getCommandMap()->register("ppx", new PermCommand($this, "ppx"));

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getLogger()->info("§bPurePermsX plugin enabled successfully !");

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