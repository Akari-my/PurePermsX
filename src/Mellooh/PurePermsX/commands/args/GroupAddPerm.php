<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;
use Mellooh\PurePermsX\PPX;

class GroupAddPerm implements SubCommand {

    private PPX $plugin;

    public function __construct(PPX $plugin){
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, array $args): void {
        if (count($args) < 2) {
            $sender->sendMessage(MessageManager::get("commands.group.usage.addperm"));
            return;
        }

        [$group, $perm] = $args;
        $gm = $this->plugin->getGroupManager();

        if (!$gm->groupExists($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
            return;
        }

        $gm->addPermission($group, $perm);
        $sender->sendMessage(MessageManager::get("commands.group.added_perm", [
            "group" => $group,
            "permission" => $perm
        ]));

        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $n = strtolower($player->getName());
            if ($this->plugin->getUserManager()->getGroup($n) === $group) {
                $this->plugin->getPermissionHandler()->applyPermissions($player);
            }
        }
    }
}