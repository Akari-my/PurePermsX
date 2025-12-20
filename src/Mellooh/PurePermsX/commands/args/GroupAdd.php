<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\libs\CommandoX\argument\StringArgument;
use Mellooh\libs\CommandoX\BaseSubCommand;
use Mellooh\libs\CommandoX\CommandContext;
use Mellooh\PurePermsX\PPX;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\plugin\Plugin;

class GroupAdd extends BaseSubCommand {

    public function __construct(Plugin $plugin, string $name = "groupadd", string $description = "Create a group", array $aliases = ["group add"]) {
        parent::__construct($plugin, $name, $description, $aliases);
    }

    protected function configure(): void {
        $this->setPermission("ppx.admin");
        $this->registerArgument(0, new StringArgument("group"));
    }

    public function onRun(CommandContext $context): void {
        $sender = $context->getSender();
        /** @var PPX $plugin */
        $plugin = $context->getPlugin();

        $group = strtolower((string)$context->getArg("group"));

        if ($plugin->getGroupManager()->createGroup($group)) {
            $sender->sendMessage(MessageManager::get("commands.group.create", ["group" => $group]));
        } else {
            $sender->sendMessage(MessageManager::get("commands.group.already_exists", ["group" => $group]));
        }
    }
}