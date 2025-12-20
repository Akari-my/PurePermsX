<?php

namespace Mellooh\libs\CommandoX;

use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

/**
 * Context object passed to onRun() of commands and subcommands.
 */
class CommandContext {

    /**
     * @param Plugin              $plugin
     * @param CommandSender       $sender
     * @param string              $label       the alias used (/cmd, /c, etc.)
     * @param array<string,mixed> $args        parsed arguments
     * @param BaseCommand         $rootCommand root command instance
     * @param BaseSubCommand|null $subCommand  the executed subcommand (if any)
     */
    public function __construct(
        private Plugin $plugin,
        private CommandSender $sender,
        private string $label,
        private array $args,
        private BaseCommand $rootCommand,
        private ?BaseSubCommand $subCommand
    ) {}

    public function getPlugin(): Plugin {
        return $this->plugin;
    }

    public function getSender(): CommandSender {
        return $this->sender;
    }

    public function getLabel(): string {
        return $this->label;
    }

    /**
     * @return array<string, mixed>
     */
    public function getArgs(): array {
        return $this->args;
    }

    public function getArg(string $name, mixed $default = null): mixed {
        return $this->args[$name] ?? $default;
    }

    public function getRootCommand(): BaseCommand {
        return $this->rootCommand;
    }

    public function getSubCommand(): ?BaseSubCommand {
        return $this->subCommand;
    }
}