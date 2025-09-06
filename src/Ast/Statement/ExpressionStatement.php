<?php

namespace RobertWesner\Wesprol\Ast\Statement;

use RobertWesner\Wesprol\Ast\Expression\Identifier;
use RobertWesner\Wesprol\Ast\Expression\Type;
use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Ast\StatementInterface;
use RobertWesner\Wesprol\Token\Token;

/**
 * TODO: is this even really necessary? I think we should disallow random expressions as we dont even have a REPL where that would be useful...
 */
readonly class ExpressionStatement implements StatementInterface
{
    public function __construct(
        public Token $token,
        public ExpressionInterface $expression,
    ) {}

    public function tokenLiteral(): string
    {
        return $this->token->literal;
    }

    public function __toString(): string
    {
        return $this->expression;
    }
}
