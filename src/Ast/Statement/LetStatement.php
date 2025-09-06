<?php

namespace RobertWesner\Wesprol\Ast\Statement;

use RobertWesner\Wesprol\Ast\Expression\Identifier;
use RobertWesner\Wesprol\Ast\Expression\Type;
use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Ast\StatementInterface;
use RobertWesner\Wesprol\Token\Token;

readonly class LetStatement implements StatementInterface
{
    public function __construct(
        public Token $token,
        public Identifier $name,
        public Type $type,
        public ExpressionInterface $value,
    ) {}

    public function tokenLiteral(): string
    {
        return $this->token->literal;
    }
}
