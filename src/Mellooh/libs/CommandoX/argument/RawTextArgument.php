<?php

namespace Mellooh\libs\CommandoX\argument;

use Mellooh\libs\CommandoX\CommandArgument;
use Mellooh\libs\CommandoX\exception\ArgumentParseException;
use pocketmine\command\CommandSender;

class RawTextArgument implements CommandArgument {

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
        return "rawtext";
    }

    public function getUsageSyntax(): string {
        // e.g. "<text...:raw>"
        $syntax = "{$this->name}...:raw";
        return $this->optional ? "[{$syntax}]" : "<{$syntax}>";
    }

    public function parse(array $words, int &$cursor, CommandSender $sender): mixed {
        $count = count($words);
        if ($cursor >= $count) {
            if ($this->optional) {
                return "";
            }
            throw new ArgumentParseException(
                "Missing argument '{$this->name}'.",
                $this->name
            );
        }

        $rest = array_slice($words, $cursor);
        $cursor = $count; // consume everything

        return implode(" ", $rest);
    }

    public function getSuggestions(CommandSender $sender): array {
        return [];
    }

}