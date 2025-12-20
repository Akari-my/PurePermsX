<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\libs\CommandoX\argument\StringArgument;
use Mellooh\libs\CommandoX\BaseSubCommand;
use Mellooh\libs\CommandoX\CommandContext;
use Mellooh\PurePermsX\PPX;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\plugin\Plugin;

class UserSetGroup extends BaseSubCommand {

    public function __construct(Plugin $plugin, string $name = "user setgroup", string $description = "Assign group to player", array $aliases = []) {
        parent::__construct($plugin, $name, $description, $aliases);
    }

    protected function configure(): void {
        $this->registerArgument(0, new StringArgument("player"));
        $this->registerArgument(1, new StringArgument("group"));
    }

    public function onRun(CommandContext $context): void {
        $sender = $context->getSender();
        /** @var PPX $plugin */
        $plugin = $context->getPlugin();

        $playerName = (string)$context->getArg("player");
        $group      = (string)$context->getArg("group");

        $gm = $plugin->getGroupManager();

        if (!$gm->groupExists($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
            return;
        }

        $um = $plugin->getUserManager();
        $um->setGroup($playerName, $group);

        $sender->sendMessage(MessageManager::get("commands.user.setgroup", [
            "player" => $playerName,
            "group"  => $group,
        ]));

        $player = $plugin->getServer()->getPlayerExact($playerName);
        if ($player !== null) {
            $plugin->getPermissionHandler()->applyPermissions($player);
        }
    }
}