<?php

namespace Mellooh\PurePermsX\commands;

use Mellooh\PurePermsX\commands\args\GroupAdd;
use Mellooh\PurePermsX\commands\args\GroupAddPerm;
use Mellooh\PurePermsX\commands\args\GroupDel;
use Mellooh\PurePermsX\commands\args\GroupList;
use Mellooh\PurePermsX\commands\args\GroupPerms;
use Mellooh\PurePermsX\commands\args\GroupRmPerm;
use Mellooh\PurePermsX\commands\args\Help;
use Mellooh\PurePermsX\commands\args\UserGroup;
use Mellooh\PurePermsX\commands\args\UserSetGroup;
use Mellooh\PurePermsX\PPX;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class PermCommand extends Command implements PluginOwned {

    private PPX $plugin;
    private array $commands = [];

    public function __construct(PPX $plugin) {
        parent::__construct("ppx", "Permission management command", "/ppx help");
        $this->plugin = $plugin;
        $this->setPermission("ppx.use");

        $this->commands = [
            "group add"      => new GroupAdd($this->plugin),
            "group del"      => new GroupDel($this->plugin),
            "group list"     => new GroupList($this->plugin),
            "group addperm"  => new GroupAddPerm($this->plugin),
            "group rmperm"   => new GroupRmPerm($this->plugin),
            "group perms"    => new GroupPerms($this->plugin),

            "user setgroup"  => new UserSetGroup($this->plugin),
            "user group"     => new UserGroup($this->plugin),
            "help" => new Help(),
        ];
    }

    public function execute(CommandSender $sender, string $label, array $args): void {
        if (count($args) === 0 || !$sender->hasPermission("ppx.use")) {
            $sender->sendMessage("§eUse: /ppx group|user <action>");
            return;
        }

        $category = strtolower(array_shift($args));
        $sub = isset($args[0]) ? strtolower($args[0]) : "";
        $lookup = $category . " " . $sub;

        if (isset($this->commands[$lookup])) {
            array_shift($args);
            $this->commands[$lookup]->execute($sender, $args);

        } elseif (isset($this->commands[$category])) {
            $this->commands[$category]->execute($sender, $args);

        } elseif ($category === "help") {
            $this->commands["help"]->execute($sender, $args);

        } else {
            $sender->sendMessage("§cUnknown subcommand. Use §e/ppx help");
        }
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }
}