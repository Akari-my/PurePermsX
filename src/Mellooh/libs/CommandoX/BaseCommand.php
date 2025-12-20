<?php

namespace Mellooh\libs\CommandoX;

use Mellooh\libs\CommandoX\exception\ArgumentParseException;
use Mellooh\libs\CommandoX\exception\CommandException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

/**
 * Root command class (registered in the CommandMap).
 */
abstract class BaseCommand extends Command {

    protected Plugin $plugin;

    /** @var CommandArgument[] indexed by position (0, 1, 2, ...) */
    protected array $arguments = [];

    /** @var BaseSubCommand[] nameLower => subcommand */
    protected array $subCommandsByName = [];

    /** @var BaseSubCommand[] aliasLower => subcommand */
    protected array $subCommandsByAlias = [];

    protected ?string $permissionMessageCustom = null;

    public function __construct(
        Plugin $plugin,
        string $name,
        string $description = "",
        array $aliases = []
    ) {
        parent::__construct($name, $description, "", $aliases);
        $this->plugin = $plugin;

        $this->configure();
    }

    /**
     * Register arguments, subcommands, permissions, etc. here.
     */
    abstract protected function configure(): void;

    /**
     * Called when NO valid subcommand is specified.
     */
    abstract public function onRun(CommandContext $context): void;

    public function getPlugin(): Plugin {
        return $this->plugin;
    }

    /**
     * Set a custom message when sender has no permission for the root command.
     */
    public function setPermissionMessageCustom(string $message): void {
        $this->permissionMessageCustom = $message;
    }

    /**
     * Register an argument for the root command.
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

    /**
     * Register a subcommand (e.g. /cmd set, /cmd info, etc.).
     */
    protected function registerSubCommand(BaseSubCommand $subCommand): void {
        $name = strtolower($subCommand->getName());
        $this->subCommandsByName[$name] = $subCommand;

        $this->subCommandsByAlias[$name] = $subCommand;
        foreach ($subCommand->getAliases() as $alias) {
            $this->subCommandsByAlias[strtolower($alias)] = $subCommand;
        }
    }

    /**
     * @return BaseSubCommand[]
     */
    public function getSubCommands(): array {
        return $this->subCommandsByName;
    }

    /**
     * Called by PocketMine when the command is executed.
     */
    public function execute(CommandSender $sender, string $label, array $args): bool {
        // Root permission
        if (!$this->testPermissionSilent($sender)) {
            $msg = $this->permissionMessageCustom
                ?? $this->getPermissionMessage()
                ?? "§cYou don't have permission to use this command.";
            $sender->sendMessage($msg);
            return true;
        }

        $sub = null;
        $consumed = 0;

        // Try to match a subcommand (including multi-word aliases)
        if (count($args) > 0) {
            [$sub, $consumed] = $this->findMatchingSubCommand($args);
        }

        if ($sub !== null) {
            $subArgs = array_slice($args, $consumed);

            if (!$sub->checkPermission($sender)) {
                $sender->sendMessage($sub->getNoPermissionMessage());
                return true;
            }

            try {
                $parsed = $this->parseArgumentsFor($sub, $subArgs, $sender);
            } catch (ArgumentParseException $e) {
                $this->sendUsageFor($sender, $sub, $e->getMessage());
                return true;
            } catch (CommandException $e) {
                $sender->sendMessage("§c" . $e->getMessage());
                return true;
            }

            $context = new CommandContext(
                $this->plugin,
                $sender,
                $label,
                $parsed,
                $this,
                $sub
            );

            $sub->onRun($context);
            return true;
        }

        // No subcommand matched -> root command
        try {
            $parsed = $this->parseArgumentsFor($this, $args, $sender);
        } catch (ArgumentParseException $e) {
            $this->sendUsageFor($sender, $this, $e->getMessage());
            return true;
        } catch (CommandException $e) {
            $sender->sendMessage("§c" . $e->getMessage());
            return true;
        }

        $context = new CommandContext(
            $this->plugin,
            $sender,
            $label,
            $parsed,
            $this,
            null
        );

        $this->onRun($context);
        return true;
    }

    /**
     * @param BaseCommand|BaseSubCommand $definition
     * @param string[]                   $words
     * @param CommandSender              $sender
     *
     * @return array<string, mixed>
     * @throws ArgumentParseException
     * @throws CommandException
     */
    private function parseArgumentsFor(BaseCommand|BaseSubCommand $definition, array $words, CommandSender $sender): array {
        $arguments   = $definition->getArguments();
        $parsed      = [];
        $cursor      = 0;
        $countWords  = count($words);

        foreach ($arguments as $argument) {
            if ($cursor >= $countWords) {
                if ($argument->isOptional()) {
                    // optional missing -> stop parsing
                    break;
                }
                throw new ArgumentParseException(
                    "Missing required argument: " . $argument->getName(),
                    $argument->getName()
                );
            }

            $value = $argument->parse($words, $cursor, $sender);
            $parsed[$argument->getName()] = $value;
        }

        // If there are leftover words -> too many arguments
        if ($cursor < $countWords) {
            throw new CommandException("Too many arguments.");
        }

        return $parsed;
    }

    /**
     * Send error + correct usage to the sender.
     */
    private function sendUsageFor(
        CommandSender $sender,
        BaseCommand|BaseSubCommand $definition,
        ?string $error = null
    ): void {
        if ($error !== null && $error !== "") {
            $sender->sendMessage("§c" . $error);
        }

        $usage = $this->buildUsageFor($definition);
        $cmdName = "/" . $this->getName();

        if ($definition instanceof BaseSubCommand) {
            $cmdName .= " " . $definition->getName();
        }

        $sender->sendMessage("§eCorrect usage: §f{$cmdName}" . ($usage !== "" ? " " . $usage : ""));
    }

    /**
     * Builds the syntax part after the command/subcommand name.
     */
    private function buildUsageFor(BaseCommand|BaseSubCommand $definition): string {
        $parts = [];
        foreach ($definition->getArguments() as $argument) {
            $parts[] = $argument->getUsageSyntax();
        }
        return implode(" ", $parts);
    }

    /**
     * Try to match a subcommand against the given args.
     * Supports:
     *   - single-word names (e.g. "groupadd")
     *   - multi-word aliases (e.g. "group add")
     *
     * Returns [BaseSubCommand|null, int $consumedArgs]
     */
    private function findMatchingSubCommand(array $args): array {
        $bestMatch = null;
        $bestLen = 0;

        foreach ($this->subCommandsByName as $sub) {
            // Patterns: name + aliases
            $patterns = array_merge(
                [$sub->getName()],
                $sub->getAliases()
            );

            foreach ($patterns as $pattern) {
                $tokens = preg_split('/\s+/', strtolower($pattern));
                $tokens = array_values(array_filter($tokens, fn($s) => $s !== ""));
                $len = count($tokens);

                if ($len === 0 || $len > count($args)) {
                    continue;
                }

                $matched = true;
                for ($i = 0; $i < $len; $i++) {
                    if (strtolower($args[$i]) !== $tokens[$i]) {
                        $matched = false;
                        break;
                    }
                }

                if ($matched && $len > $bestLen) {
                    $bestLen = $len;
                    $bestMatch = $sub;
                }
            }
        }

        return [$bestMatch, $bestLen];
    }
}