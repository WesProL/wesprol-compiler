<?php

namespace RobertWesner\Wesprol\Ast;

use Stringable;

interface NodeInterface extends Stringable
{
    public function tokenLiteral(): string;
}
