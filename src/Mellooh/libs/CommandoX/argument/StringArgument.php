<?php

namespace Mellooh\libs\CommandoX\argument;

use Mellooh\libs\CommandoX\CommandArgument;
use Mellooh\libs\CommandoX\exception\ArgumentParseException;
use pocketmine\command\CommandSender;

class StringArgument implements CommandArgument {

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
        return "string";
    }

    public function getUsageSyntax(): string {
        $syntax = "{$this->name}:string";
        return $this->optional ? "[{$syntax}]" : "<{$syntax}>";
    }

    public function parse(array $words, int &$cursor, CommandSender $sender): mixed {
        $value = $words[$cursor] ?? "";
        $cursor++;

        if ($value === "" && !$this->optional) {
            throw new ArgumentParseException("Invalid value for '{$this->name}'.", $this->name);
        }

        return $value;
    }

    public function getSuggestions(CommandSender $sender): array {
        return [];
    }

}