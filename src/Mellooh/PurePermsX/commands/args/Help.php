<?php

namespace Mellooh\PurePermsX\commands\args;

use Mellooh\PurePermsX\commands\SubCommand;
use pocketmine\command\CommandSender;

class Help implements SubCommand{

    public function execute(CommandSender $sender, array $args): void {
        $sender->sendMessage("§e§l☰ PurePermsX Help");
        $sender->sendMessage("§7Group commands:");
        $sender->sendMessage(" §b/ppx group add <name> §7- Create a group");
        $sender->sendMessage(" §b/ppx group del <name> §7- Delete a group");
        $sender->sendMessage(" §b/ppx group list §7- Show all groups");
        $sender->sendMessage(" §b/ppx group perms <group> §7- Show group permissions");
        $sender->sendMessage(" §b/ppx group addperm <group> <permission> §7- Add permission");
        $sender->sendMessage(" §b/ppx group rmperm <group> <permission> §7- Remove permission");

        $sender->sendMessage("");
        $sender->sendMessage("§7User commands:");
        $sender->sendMessage(" §b/ppx user setgroup <player> <group> §7- Assign group");
        $sender->sendMessage(" §b/ppx user group <player> §7- Show player group");
    }
}