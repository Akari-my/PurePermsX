<?php

namespace Mellooh\libs\CommandoX\argument;

use Mellooh\libs\CommandoX\CommandArgument;
use Mellooh\libs\CommandoX\exception\ArgumentParseException;
use pocketmine\command\CommandSender;

class IntegerArgument implements CommandArgument {

    public function __construct(
        private string $name,
        private bool $optional = false,
        private ?int $min = null,
        private ?int $max = null
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function isOptional(): bool {
        return $this->optional;
    }

    public function getTypeName(): string {
        return "int";
    }

    public function getUsageSyntax(): string {
        $syntax = "{$this->name}:int";
        return $this->optional ? "[{$syntax}]" : "<{$syntax}>";
    }

    public function parse(array $words, int &$cursor, CommandSender $sender): mixed {
        $raw = $words[$cursor] ?? "";
        $cursor++;

        if (!is_numeric($raw)) {
            throw new ArgumentParseException("Argument '{$this->name}' must be a number.", $this->name);
        }

        $value = (int)$raw;

        if ($this->min !== null && $value < $this->min) {
            throw new ArgumentParseException(
                "Minimum value for '{$this->name}' is {$this->min}.",
                $this->name
            );
        }

        if ($this->max !== null && $value > $this->max) {
            throw new ArgumentParseException(
                "Maximum value for '{$this->name}' is {$this->max}.",
                $this->name
            );
        }

        return $value;
    }

    public function getSuggestions(CommandSender $sender): array {
        return [];
    }
}