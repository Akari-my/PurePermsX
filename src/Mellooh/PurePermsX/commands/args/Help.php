<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\libs\CommandoX\BaseSubCommand;
use Mellooh\libs\CommandoX\CommandContext;
use pocketmine\plugin\Plugin;

class Help extends BaseSubCommand {

    public function __construct(Plugin $plugin, string $name = "help", string $description = "Show PurePermsX help", array $aliases = []) {
        parent::__construct($plugin, $name, $description, $aliases);
    }

    protected function configure(): void {
    }

    public function onRun(CommandContext $context): void {
        $sender = $context->getSender();

        $sender->sendMessage("§e§l☰ PurePermsX Help");
        $sender->sendMessage("§7Group commands:");
        $sender->sendMessage(" §b/ppx groupadd <name> §7- Create a group");
        $sender->sendMessage(" §b/ppx groupdel <name> §7- Delete a group");
        $sender->sendMessage(" §b/ppx grouplist §7- Show all groups");
        $sender->sendMessage(" §b/ppx groupperms <group> §7- Show group permissions");
        $sender->sendMessage(" §b/ppx groupaddperm <group> <permission> §7- Add permission");
        $sender->sendMessage(" §b/ppx grouprmperm <group> <permission> §7- Remove permission");

        $sender->sendMessage("");
        $sender->sendMessage("§7User commands:");
        $sender->sendMessage(" §b/ppx usersetgroup <player> <group> §7- Assign group");
        $sender->sendMessage(" §b/ppx usergroup <player> §7- Show player group");
    }
}