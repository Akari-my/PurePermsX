<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;
use Mellooh\PurePermsX\PPX;

class GroupPerms implements SubCommand {

    public function execute(CommandSender $sender, array $args): void {
        if (!isset($args[0])) {
            $sender->sendMessage(MessageManager::get("commands.usage.perms"));
            return;
        }

        $group = strtolower($args[0]);
        $gm = PPX::getInstance()->getGroupManager();
        $perms = $gm->getPermissions($group);

        if (!$gm->groupExists($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
            return;
        }

        if (empty($perms)) {
            $sender->sendMessage(MessageManager::get("commands.group.no_perms", ["group" => $group]));
            return;
        }

        $sender->sendMessage(MessageManager::get("commands.group.perms_title", ["group" => $group]));
        foreach ($perms as $perm) {
            $sender->sendMessage(" §a- $perm");
        }
    }
}