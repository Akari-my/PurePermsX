<?php

namespace Mellooh\PurePermsX\commands;

use Mellooh\libs\CommandoX\BaseCommand;
use Mellooh\libs\CommandoX\CommandContext;
use Mellooh\PurePermsX\commands\args\GroupAdd;
use Mellooh\PurePermsX\commands\args\GroupAddPerm;
use Mellooh\PurePermsX\commands\args\GroupDel;
use Mellooh\PurePermsX\commands\args\GroupList;
use Mellooh\PurePermsX\commands\args\GroupPerms;
use Mellooh\PurePermsX\commands\args\GroupRmPerm;
use Mellooh\PurePermsX\commands\args\Help;
use Mellooh\PurePermsX\commands\args\UserGroup;
use Mellooh\PurePermsX\commands\args\UserSetGroup;
use Mellooh\PurePermsX\PPX;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class PermCommand extends BaseCommand implements PluginOwned {

    public function __construct(PPX $plugin, string $name = "ppx") {
        parent::__construct($plugin, $name, "Permission management command", []);
    }

    protected function configure(): void {
        $this->setPermission("ppx.use");
        $this->setPermissionMessageCustom("§cYou don't have permission to use /ppx.");

        $this->registerSubCommand(new GroupAdd($this->plugin, "groupadd", "Create a group", ["group add"]));
        $this->registerSubCommand(new GroupDel($this->plugin, "groupdel", "Delete a group", ["group del"]));
        $this->registerSubCommand(new GroupList($this->plugin, "grouplist", "List all groups", ["group list"]));
        $this->registerSubCommand(new GroupPerms($this->plugin, "groupperms", "Show group permissions", ["group perms"]));
        $this->registerSubCommand(new GroupAddPerm($this->plugin, "groupaddperm", "Add permission to group", ["group addperm"]));
        $this->registerSubCommand(new GroupRmPerm($this->plugin, "grouprmperm", "Remove permission from group", ["group rmperm"]));

        $this->registerSubCommand(new UserSetGroup($this->plugin, "usersetgroup", "Assign group to player", ["user setgroup"]));
        $this->registerSubCommand(new UserGroup($this->plugin, "usergroup", "Show player group", ["user group"]));

        $this->registerSubCommand(new Help($this->plugin, "help", "Show PurePermsX help"));
    }

    public function onRun(CommandContext $context): void {
        $sender = $context->getSender();
        $sender->sendMessage("§eUse: §f/ppx help");
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }
}