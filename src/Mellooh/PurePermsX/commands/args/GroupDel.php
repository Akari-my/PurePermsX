<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\libs\CommandoX\argument\StringArgument;
use Mellooh\libs\CommandoX\BaseSubCommand;
use Mellooh\libs\CommandoX\CommandContext;
use Mellooh\PurePermsX\PPX;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\plugin\Plugin;

class GroupDel extends BaseSubCommand {

    public function __construct(Plugin $plugin, string $name = "group del", string $description = "Delete a group", array $aliases = []) {
        parent::__construct($plugin, $name, $description, $aliases);
    }

    protected function configure(): void {
        $this->registerArgument(0, new StringArgument("group"));
    }

    public function onRun(CommandContext $context): void {
        $sender = $context->getSender();
        /** @var PPX $plugin */
        $plugin = $context->getPlugin();

        $group = strtolower((string)$context->getArg("group"));
        $gm    = $plugin->getGroupManager();

        if ($gm->deleteGroup($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.delete", ["group" => $group]));

            foreach ($plugin->getServer()->getOnlinePlayers() as $player) {
                $name = strtolower($player->getName());
                $um = $plugin->getUserManager();
                    if ($um->getGroup($name) === $group) {
                        $um->setGroup($name, "guest");
                        $plugin->getPermissionHandler()->applyPermissions($player);
                    }
            }
        } else {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
        }
    }
}