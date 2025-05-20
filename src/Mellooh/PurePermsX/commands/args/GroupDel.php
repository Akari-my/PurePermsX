<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;
use Mellooh\PurePermsX\PPX;

class GroupDel implements SubCommand
{

    public function execute(CommandSender $sender, array $args): void {
        if (!isset($args[0])) {
            $sender->sendMessage(MessageManager::get("commands.usage.groupdel"));
            return;
        }

        $group = strtolower($args[0]);
        $gm = PPX::getInstance()->getGroupManager();

        if ($gm->deleteGroup($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.delete", ["group" => $group]));

            foreach (PPX::getInstance()->getServer()->getOnlinePlayers() as $player) {
                $name = strtolower($player->getName());
                $um = PPX::getInstance()->getUserManager();
                if ($um->getGroup($name) === $group) {
                    $um->setGroup($name, "default");
                    PPX::getInstance()->getPermissionHandler()->applyPermissions($player);
                }
            }
        } else {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
        }
    }
}