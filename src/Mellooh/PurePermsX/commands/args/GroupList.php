<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\libs\CommandoX\BaseSubCommand;
use Mellooh\libs\CommandoX\CommandContext;
use Mellooh\PurePermsX\PPX;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\plugin\Plugin;

class GroupList extends BaseSubCommand {

    public function __construct(Plugin $plugin, string $name = "group list", string $description = "List all groups", array $aliases = []) {
        parent::__construct($plugin, $name, $description, $aliases);
    }

    protected function configure(): void {
    }

    public function onRun(CommandContext $context): void {
        $sender = $context->getSender();
        /** @var PPX $plugin */
        $plugin = $context->getPlugin();

        $groups = $plugin->getGroupManager()->getGroups();

        if (empty($groups)) {
            $sender->sendMessage(MessageManager::get("commands.group.no_groups"));
            return;
        }

        $sender->sendMessage(MessageManager::get("commands.group.list_title"));
        foreach ($groups as $group) {
            $sender->sendMessage(" §b- {$group}");
        }
    }
}