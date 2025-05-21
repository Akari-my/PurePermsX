<?php

namespace Mellooh\PurePermsX\api;

use Mellooh\PurePermsX\handler\PermissionHandler;
use Mellooh\PurePermsX\manager\GroupManager;
use Mellooh\PurePermsX\manager\UserManager;
use Mellooh\PurePermsX\PPX;

class PurePermsX{

    /** @var PPX */
    private static PPX $plugin;

    public static function init(PPX $plugin): void {
        self::$plugin = $plugin;
    }

    public static function getPermissionHandler(): PermissionHandler {
        return self::$plugin->getPermissionHandler();
    }

    public static function getGroupManager(): GroupManager {
        return self::$plugin->getGroupManager();
    }

    public static function getUserManager(): UserManager {
        return self::$plugin->getUserManager();
    }

    public static function getPlugin(): PPX {
        return self::$plugin;
    }
}