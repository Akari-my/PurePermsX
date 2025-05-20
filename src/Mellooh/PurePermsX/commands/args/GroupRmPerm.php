<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;
use Mellooh\PurePermsX\PPX;

class GroupRmPerm implements SubCommand {

    public function execute(CommandSender $sender, array $args): void {
        if (count($args) < 2) {
            $sender->sendMessage(MessageManager::get("commands.group.usage.rmperm"));
            return;
        }

        [$group, $perm] = $args;
        $gm = PPX::getInstance()->getGroupManager();

        if (!$gm->groupExists($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
            return;
        }

        $gm->removePermission($group, $perm);
        $sender->sendMessage(MessageManager::get("commands.group.removed_perm", [
            "group" => $group,
            "permission" => $perm
        ]));

        foreach (PPX::getInstance()->getServer()->getOnlinePlayers() as $player) {
            $n = strtolower($player->getName());
            if (PPX::getInstance()->getUserManager()->getGroup($n) === $group) {
                PPX::getInstance()->getPermissionHandler()->applyPermissions($player);
            }
        }
    }
}