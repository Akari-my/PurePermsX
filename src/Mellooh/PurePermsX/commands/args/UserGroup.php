<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;
use Mellooh\PurePermsX\PPX;

class UserGroup implements SubCommand {

    public function execute(CommandSender $sender, array $args): void {
        if (!isset($args[0])) {
            $sender->sendMessage(MessageManager::get("commands.user.usage_group"));
            return;
        }

        $playerName = strtolower($args[0]);
        $group = PPX::getInstance()->getUserManager()->getGroup($playerName);

        $sender->sendMessage(MessageManager::get("commands.user.group", [
            "player" => $playerName,
            "group" => $group
        ]));
    }
}