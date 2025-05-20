<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;
use Mellooh\PurePermsX\PPX;

class UserSetGroup implements SubCommand {

    public function execute(CommandSender $sender, array $args): void {
        if (count($args) < 2) {
            $sender->sendMessage(MessageManager::get("commands.user.usage_setgroup"));
            return;
        }

        [$playerName, $group] = $args;
        $gm = PPX::getInstance()->getGroupManager();

        if (!$gm->groupExists($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
            return;
        }

        $um = PPX::getInstance()->getUserManager();
        $um->setGroup($playerName, $group);
        $sender->sendMessage(MessageManager::get("commands.user.setgroup", [
            "player" => $playerName,
            "group" => $group
        ]));

        $player = PPX::getInstance()->getServer()->getPlayerExact($playerName);
        if ($player !== null) {
            PPX::getInstance()->getPermissionHandler()->applyPermissions($player);
        }
    }
}