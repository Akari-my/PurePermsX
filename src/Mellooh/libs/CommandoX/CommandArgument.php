<?php

namespace Mellooh\libs\CommandoX;

use Mellooh\libs\CommandoX\exception\ArgumentParseException;
use pocketmine\command\CommandSender;

interface  CommandArgument {

    public function getName(): string;

    public function isOptional(): bool;

    public function getTypeName(): string;

    /**
     * Syntax representation for usage help
     * e.g. "<name:string>", "[age:int]".
     */
    public function getUsageSyntax(): string;

    /**
     * Parses this argument from the raw words.
     *
     * @param string[]      $words   raw arguments (split by space)
     * @param int           $cursor  current index; the argument may consume 1+ words
     * @param CommandSender $sender
     *
     * @return mixed parsed value
     * @throws ArgumentParseException if parsing fails
     */
    public function parse(array $words, int &$cursor, CommandSender $sender): mixed;

    /**
     * Suggestions for tab completion (if you add it later).
     *
     * @return string[]
     */
    public function getSuggestions(CommandSender $sender): array;

}