<?php

namespace RobertWesner\Wesprol\Ast\Expression;

use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Token\Token;
use RobertWesner\Wesprol\Token\TokenType;

class PrefixExpression implements ExpressionInterface
{
    public function __construct(
        public Token $token,
        public TokenType $operator,
        public ExpressionInterface $right,
    ) {}

    public function tokenLiteral(): string
    {
        return $this->token->literal;
    }

    public function __toString(): string
    {
        return '('
            . $this->operator->value
            . $this->right
            . ')';
    }
}
