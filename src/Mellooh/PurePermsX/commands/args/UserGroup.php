<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\libs\CommandoX\argument\StringArgument;
use Mellooh\libs\CommandoX\BaseSubCommand;
use Mellooh\libs\CommandoX\CommandContext;
use Mellooh\PurePermsX\commands\SubCommand;
use Mellooh\PurePermsX\PPX;
use Mellooh\PurePermsX\utils\MessageManager;
use pocketmine\plugin\Plugin;

class UserGroup extends BaseSubCommand {

    public function __construct(Plugin $plugin, string $name = "user group", string $description = "Show player's group", array $aliases = []) {
        parent::__construct($plugin, $name, $description, $aliases);
    }

    protected function configure(): void {
        $this->registerArgument(0, new StringArgument("player"));
    }

    public function onRun(CommandContext $context): void {
        $sender = $context->getSender();
        /** @var PPX $plugin */
        $plugin = $context->getPlugin();

        $playerName = strtolower((string)$context->getArg("player"));
        $group = $plugin->getUserManager()->getGroup($playerName);

        $sender->sendMessage(MessageManager::get("commands.user.group", [
            "player" => $playerName,
            "group"  => $group,
        ]));
    }
}