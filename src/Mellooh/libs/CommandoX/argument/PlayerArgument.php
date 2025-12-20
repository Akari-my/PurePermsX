<?php

namespace Mellooh\libs\CommandoX\argument;

use Mellooh\libs\CommandoX\CommandArgument;
use Mellooh\libs\CommandoX\exception\ArgumentParseException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerArgument implements CommandArgument {

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
        return "player";
    }

    public function getUsageSyntax(): string {
        $syntax = "{$this->name}:player";
        return $this->optional ? "[{$syntax}]" : "<{$syntax}>";
    }

    public function parse(array $words, int &$cursor, CommandSender $sender): mixed {
        $raw = $words[$cursor] ?? "";
        $cursor++;

        if ($raw === "" && $this->optional) {
            return null;
        }

        $player = $this->findPlayerByPrefix($raw);
        if (!$player instanceof Player) {
            throw new ArgumentParseException("Player '{$raw}' not found.", $this->name);
        }

        return $player;
    }

    /**
     * Simple prefix search among online players.
     */
    private function findPlayerByPrefix(string $prefix): ?Player {
        $prefixLower = strtolower($prefix);
        $found = null;
        $foundLen = PHP_INT_MAX;

        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $name = $player->getName();
            if (str_starts_with(strtolower($name), $prefixLower)) {
                $len = strlen($name);
                if ($len < $foundLen) {
                    $found = $player;
                    $foundLen = $len;
                }
            }
        }

        return $found;
    }

    public function getSuggestions(CommandSender $sender): array {
        $names = [];
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $names[] = $player->getName();
        }
        return $names;
    }

}