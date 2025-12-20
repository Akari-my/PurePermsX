<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\libs\CommandoX\argument\StringArgument;
use Mellooh\libs\CommandoX\BaseSubCommand;
use Mellooh\libs\CommandoX\CommandContext;
use Mellooh\PurePermsX\PPX;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\plugin\Plugin;

class GroupPerms extends BaseSubCommand {

    public function __construct(Plugin $plugin, string $name = "group perms", string $description = "Show group permissions", array $aliases = []) {
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

        if (!$gm->groupExists($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.does_not_exist", ["group" => $group]));
            return;
        }

        $perms = $gm->getPermissions($group);

        if (empty($perms)) {
            $sender->sendMessage(MessageManager::get("commands.group.no_perms", ["group" => $group]));
            return;
        }

        $sender->sendMessage(MessageManager::get("commands.group.perms_title", ["group" => $group]));
        foreach ($perms as $perm) {
            $sender->sendMessage(" §a- {$perm}");
        }
    }
}