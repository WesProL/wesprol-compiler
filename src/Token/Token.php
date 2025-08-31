<?php

namespace RobertWesner\Wesprol\Token;

readonly class Token
{
    public function __construct(
        public TokenType $type,
        public string $literal,
        public int $line,
        public int $column,
    ) {}
}
