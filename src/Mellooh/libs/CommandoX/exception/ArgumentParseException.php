<?php

namespace Mellooh\libs\CommandoX\exception;

/**
 * Thrown when a single argument fails to parse.
 */
class ArgumentParseException extends CommandException {

    public function __construct(
        string $message,
        private ?string $argumentName = null
    ) {
        parent::__construct($message);
    }

    public function getArgumentName(): ?string {
        return $this->argumentName;
    }
}