<?php

namespace RobertWesner\Wesprol\Ast;

class Program implements NodeInterface
{
    /** @var StatementInterface[] */
    public array $statements = [];

    public function tokenLiteral(): string
    {
        if (count($this->statements) > 0) {
            return $this->statements[0]->tokenLiteral();
        }

        return "";
    }

    public function __toString(): string
    {
        if (count($this->statements) > 0) {
            return $this->statements[0];
        }

        return "";
    }
}
