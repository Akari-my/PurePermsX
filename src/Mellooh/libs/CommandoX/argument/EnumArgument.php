<?php

namespace Mellooh\libs\CommandoX\argument;

use Mellooh\libs\CommandoX\CommandArgument;
use Mellooh\libs\CommandoX\exception\ArgumentParseException;
use pocketmine\command\CommandSender;

class EnumArgument implements CommandArgument {

    /**
     * @param string   $name
     * @param string[] $values   valid values (recommended: lowercase)
     * @param bool     $optional
     */
    public function __construct(
        private string $name,
        private array $values,
        private bool $optional = false
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function isOptional(): bool {
        return $this->optional;
    }

    public function getTypeName(): string {
        return "enum(" . implode("|", $this->values) . ")";
    }

    public function getUsageSyntax(): string {
        $syntax = "{$this->name}:" . implode("|", $this->values);
        return $this->optional ? "[{$syntax}]" : "<{$syntax}>";
    }

    public function parse(array $words, int &$cursor, CommandSender $sender): mixed {
        $raw = strtolower(trim($words[$cursor] ?? ""));
        $cursor++;

        if ($raw === "" && $this->optional) {
            return null;
        }

        if (!in_array($raw, $this->values, true)) {
            throw new ArgumentParseException(
                "Invalid value for '{$this->name}'. Allowed: " . implode(", ", $this->values),
                $this->name
            );
        }

        return $raw;
    }

    public function getSuggestions(CommandSender $sender): array {
        return $this->values;
    }

}