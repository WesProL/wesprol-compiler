<?php

namespace RobertWesner\Wesprol\Ast\Expression;

use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Token\Token;

class IntLiteral implements ExpressionInterface
{
    public function __construct(
        public Token $token,
        // intentionally not changed to any PHP-Internal types
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
