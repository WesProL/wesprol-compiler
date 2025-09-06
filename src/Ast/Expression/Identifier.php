<?php

namespace RobertWesner\Wesprol\Ast\Expression;

use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Token\Token;

readonly class Identifier implements ExpressionInterface
{
    public function __construct(
        public Token $token,
        public string $value,
    ) {}

    public function tokenLiteral(): string
    {
        return $this->token->literal;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
