<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\command\CommandSender;
use Mellooh\PurePermsX\PPX;

class UserSetGroup implements SubCommand {

    private PPX $plugin;

    public function __construct(PPX $plugin){
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, array $args): void {
        if (count($args) < 2) {
            $sender->sendMessage(MessageManager::get("commands.user.usage_setgroup"));
            return;
        }

        [$playerName, $group] = $args;
        $gm = $this->plugin->getGroupManager();

        if (!$gm->groupExists($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
            return;
        }

        $um = $this->plugin->getUserManager();
        $um->setGroup($playerName, $group);
        $sender->sendMessage(MessageManager::get("commands.user.setgroup", [
            "player" => $playerName,
            "group" => $group
        ]));

        $player = $this->plugin->getServer()->getPlayerExact($playerName);
        if ($player !== null) {
            $this->plugin->getPermissionHandler()->applyPermissions($player);
        }
    }
}