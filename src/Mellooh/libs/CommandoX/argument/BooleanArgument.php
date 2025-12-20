<?php

namespace Mellooh\libs\CommandoX\argument;

use Mellooh\libs\CommandoX\CommandArgument;
use Mellooh\libs\CommandoX\exception\ArgumentParseException;
use pocketmine\command\CommandSender;

class BooleanArgument implements CommandArgument {

    public function __construct(
        private string $name,
        private bool $optional = false
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function isOptional(): bool {
        return $this->optional;
    }

    public function getTypeName(): string {
        return "bool";
    }

    public function getUsageSyntax(): string {
        $syntax = "{$this->name}:bool";
        return $this->optional ? "[{$syntax}]" : "<{$syntax}>";
    }

    public function parse(array $words, int &$cursor, CommandSender $sender): mixed {
        $raw = strtolower(trim($words[$cursor] ?? ""));
        $cursor++;

        if ($raw === "" && $this->optional) {
            // default for optional boolean if not provided
            return false;
        }

        $trueValues  = ["true", "1", "yes", "y", "on"];
        $falseValues = ["false", "0", "no", "n", "off"];

        if (in_array($raw, $trueValues, true)) {
            return true;
        }

        if (in_array($raw, $falseValues, true)) {
            return false;
        }

        throw new ArgumentParseException(
            "Argument '{$this->name}' must be true/false (or similar).",
            $this->name
        );
    }

    public function getSuggestions(CommandSender $sender): array {
        return ["true", "false"];
    }

}