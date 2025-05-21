<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\PPX;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;

class GroupAdd implements SubCommand {

    private PPX $plugin;

    public function __construct(PPX $plugin){
        $this->plugin = $plugin;
    }


    public function execute(CommandSender $sender, array $args): void {
        if (!$sender->hasPermission("ppx.admin")) return;

        if (!isset($args[0])) {
            $sender->sendMessage(MessageManager::get("commands.usage.groupadd"));
            return;
        }

        $group = strtolower($args[0]);
        if ($this->plugin->getGroupManager()->createGroup($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.create", ["group" => $group]));
        } else {
            $sender->sendMessage(MessageManager::get("commands.group.already_exists", ["group" => $group]));
        }
    }
}