<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\PPX;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;

class GroupList implements SubCommand {

    public function execute(CommandSender $sender, array $args): void {
        $groups = PPX::getInstance()->getGroupManager()->getGroups();

        if (empty($groups)) {
            $sender->sendMessage(MessageManager::get("commands.group.no_groups"));
            return;
        }

        $sender->sendMessage(MessageManager::get("commands.group.list_title"));
        foreach ($groups as $group) {
            $sender->sendMessage(" §b- $group");
        }
    }
}