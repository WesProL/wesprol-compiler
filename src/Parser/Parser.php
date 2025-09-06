<?php

namespace RobertWesner\Wesprol\Parser;

use RobertWesner\Wesprol\Ast\Expression\Identifier;
use RobertWesner\Wesprol\Ast\Expression\Type;
use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Ast\Program;
use RobertWesner\Wesprol\Ast\Statement\LetStatement;
use RobertWesner\Wesprol\Ast\StatementInterface;
use RobertWesner\Wesprol\Lexer\Lexer;
use RobertWesner\Wesprol\Token\Token;
use RobertWesner\Wesprol\Token\TokenType;

class Parser
{
    private Token $tokenCurrent;
    private Token $tokenPeek;

    public function __construct(
        private readonly Lexer $lexer,
    ) {
        // populate current and peek
        $this->nextToken();
        $this->nextToken();
    }

    public function parse(): Program
    {
        $program = new Program();

        while ($this->tokenCurrent->type != TokenType::Eof) {
            $statement = $this->parseStatement();
            if ($statement !== null) {
                $program->statements[] = $statement;
            }
            $this->nextToken();
        }

        return $program;
    }

    private function nextToken(): void
    {
        if (isset($this->tokenPeek)) {
            $this->tokenCurrent = $this->tokenPeek;
        }

        $this->tokenPeek = $this->lexer->nextToken();
    }

    private function currentIs(TokenType $type): bool
    {
        return $this->tokenCurrent->type === $type;
    }

    private function peekIs(TokenType $type): bool
    {
        return $this->tokenPeek->type === $type;
    }

    private function expectPeek(TokenType $type): bool
    {
        if ($this->peekIs($type)) {
            $this->nextToken();
            return true;
        }

        return false;
    }

    private function expectPeekIsType(): bool
    {
        foreach (TokenType::TYPES as $type) {
            if ($this->peekIs($type)) {
                $this->nextToken();
                return true;
            }
        }

        return false;
    }

    private function parseStatement(): ?StatementInterface
    {
        switch ($this->tokenCurrent->type) {
            case TokenType::Let:
                return $this->parseLetStatement();
            default:
                return null;
        }
    }

    private function parseLetStatement(): ?LetStatement
    {
        $letToken = $this->tokenCurrent;

        if (!$this->expectPeek(TokenType::Identifier)) {
            return null;
        }
        $name = new Identifier($this->tokenCurrent, $this->tokenCurrent->literal);

        if (!$this->expectPeekIsType()) {
            return null;
        }
        $type = new Type($this->tokenCurrent, $this->tokenCurrent->type);

        if (!$this->expectPeek(TokenType::Equals)) {
            return null;
        }
        $this->nextToken();

        $value = $this->parseExpression();

        return new LetStatement($letToken, $name, $type, $value);
    }

    private function parseExpression(): ?ExpressionInterface
    {
        while (!$this->currentIs(TokenType::Semicolon) && !$this->currentIs(TokenType::Eof)) {
            $this->nextToken();
        }

        return new class implements ExpressionInterface {
            public function tokenLiteral(): string
            {
                return "NOT_IMPLEMENTED";
            }
        };
    }
}
