<?php

namespace RobertWesner\Wesprol\Parser;

class ParserError
{
    public function __construct(
        public int $line,
        public int $column,
        public string $message,
    ) {}
}
