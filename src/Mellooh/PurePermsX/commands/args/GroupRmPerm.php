<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\libs\CommandoX\argument\StringArgument;
use Mellooh\libs\CommandoX\BaseSubCommand;
use Mellooh\libs\CommandoX\CommandContext;
use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\PPX;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\plugin\Plugin;

class GroupRmPerm extends BaseSubCommand {

    public function __construct(Plugin $plugin, string $name = "group rmperm", string $description = "Remove permission from a group", array $aliases = []) {
        parent::__construct($plugin, $name, $description, $aliases);
    }

    protected function configure(): void {
        $this->registerArgument(0, new StringArgument("group"));
        $this->registerArgument(1, new StringArgument("permission"));
    }

    public function onRun(CommandContext $context): void {
        $sender = $context->getSender();
        /** @var PPX $plugin */
        $plugin = $context->getPlugin();

        $group = (string)$context->getArg("group");
        $perm  = (string)$context->getArg("permission");

        $gm = $plugin->getGroupManager();

        if (!$gm->groupExists($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
            return;
        }

        $gm->removePermission($group, $perm);
        $sender->sendMessage(MessageManager::get("commands.group.removed_perm", [
            "group"      => $group,
            "permission" => $perm,
        ]));

        foreach ($plugin->getServer()->getOnlinePlayers() as $player) {
            $n = strtolower($player->getName());
            if ($plugin->getUserManager()->getGroup($n) === $group) {
                $plugin->getPermissionHandler()->applyPermissions($player);
            }
        }
    }
}