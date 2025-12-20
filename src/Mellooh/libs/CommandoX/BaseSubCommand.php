<?php

namespace Mellooh\libs\CommandoX;

use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

/**
 * Represents a subcommand (e.g. /maincmd set, /maincmd info, etc.).
 */
abstract class BaseSubCommand {

    protected Plugin $plugin;

    protected string $name;

    /** @var string[] */
    protected array $aliases = [];

    protected string $description;

    protected ?string $permission = null;

    protected ?string $noPermissionMessage = null;

    /** @var CommandArgument[] indexed by position (0,1,2,...) */
    protected array $arguments = [];

    public function __construct(
        Plugin $plugin,
        string $name,
        string $description = "",
        array $aliases = []
    ) {
        $this->plugin      = $plugin;
        $this->name        = $name;
        $this->description = $description;
        $this->aliases     = $aliases;

        $this->configure();
    }

    /**
     * Register arguments, permissions, etc. for this subcommand.
     */
    abstract protected function configure(): void;

    /**
     * Called when this subcommand is executed successfully.
     */
    abstract public function onRun(CommandContext $context): void;

    public function getPlugin(): Plugin {
        return $this->plugin;
    }

    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getAliases(): array {
        return $this->aliases;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setPermission(?string $permission): void {
        $this->permission = $permission;
    }

    public function getPermission(): ?string {
        return $this->permission;
    }

    public function setNoPermissionMessage(string $message): void {
        $this->noPermissionMessage = $message;
    }

    public function getNoPermissionMessage(): string {
        return $this->noPermissionMessage
            ?? "§cYou don't have permission to use this subcommand.";
    }

    public function checkPermission(CommandSender $sender): bool {
        if ($this->permission === null || $this->permission === "") {
            return true;
        }
        return $sender->hasPermission($this->permission);
    }

    /**
     * Register an argument for this subcommand.
     */
    protected function registerArgument(int $index, CommandArgument $argument): void {
        $this->arguments[$index] = $argument;
        ksort($this->arguments);
    }

    /**
     * @return CommandArgument[]
     */
    public function getArguments(): array {
        return $this->arguments;
    }
}