<?php

namespace RobertWesner\Wesprol\Ast\Expression;

use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Token\Token;
use RobertWesner\Wesprol\Token\TokenType;

readonly class Type implements ExpressionInterface
{
    public function __construct(
        public Token $token,
        public TokenType $type,
    ) {}

    public function tokenLiteral(): string
    {
        return $this->token->literal;
    }
}
