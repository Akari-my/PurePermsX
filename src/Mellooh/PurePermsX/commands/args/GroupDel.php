<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;
use Mellooh\PurePermsX\PPX;

class GroupDel implements SubCommand{

    private PPX $plugin;

    public function __construct(PPX $plugin){
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, array $args): void {
        if (!isset($args[0])) {
            $sender->sendMessage(MessageManager::get("commands.usage.groupdel"));
            return;
        }

        $group = strtolower($args[0]);
        $gm = $this->plugin->getGroupManager();

        if ($gm->deleteGroup($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.delete", ["group" => $group]));

            foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                $name = strtolower($player->getName());
                $um = $this->plugin->getUserManager();
                if ($um->getGroup($name) === $group) {
                    $um->setGroup($name, "default");
                    $this->plugin->getPermissionHandler()->applyPermissions($player);
                }
            }
        } else {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
        }
    }
}