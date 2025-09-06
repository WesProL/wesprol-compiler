<?php

namespace RobertWesner\Wesprol\Parser;

use RobertWesner\Wesprol\Ast\Expression\Identifier;
use RobertWesner\Wesprol\Ast\Expression\Type;
use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Ast\Program;
use RobertWesner\Wesprol\Ast\Statement\ExpressionStatement;
use RobertWesner\Wesprol\Ast\Statement\GiveStatement;
use RobertWesner\Wesprol\Ast\Statement\LetStatement;
use RobertWesner\Wesprol\Ast\Statement\ReturnStatement;
use RobertWesner\Wesprol\Ast\StatementInterface;
use RobertWesner\Wesprol\Lexer\Lexer;
use RobertWesner\Wesprol\Token\Token;
use RobertWesner\Wesprol\Token\TokenType;

class Parser
{
    private Token $tokenCurrent;
    private Token $tokenPeek;
    /** @var ParserError[] */
    private array $errors = [];

    private array $prefixParseFunctions = [];
    private array $infixParseFunctions = [];

    public function __construct(
        private readonly Lexer $lexer,
    ) {
        // populate current and peek
        $this->nextToken();
        $this->nextToken();

        $this->registerPrefix(TokenType::Identifier, function (): ExpressionInterface {
            return new Identifier($this->tokenCurrent, $this->tokenCurrent->literal);
        });
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

    /**
     * @return ParserError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    private function registerPrefix(TokenType $type, callable $function): void
    {
        $this->prefixParseFunctions[$type->value] = $function;
    }

    private function registerInfix(TokenType $type, callable $function): void
    {
        $this->infixParseFunctions[$type->value] = $function;
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

        $this->errors[] = new ParserError(
            $this->tokenPeek->line,
            $this->tokenPeek->column,
            sprintf(
                'Invalid token of type "%s" and value "%s" on line %d column %d. Expected type "%s".',
                $this->tokenPeek->type->value,
                $this->tokenPeek->literal,
                $this->tokenPeek->line,
                $this->tokenPeek->column,
                $type->value,
            ),
        );

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
            case TokenType::Return:
                return $this->parseReturnStatement();
            case TokenType::Give:
                return $this->parseGiveStatement();
            default:
                return $this->parseExpressionStatement();
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

        $value = $this->parseExpression(Precedence::Lowest);

        return new LetStatement($letToken, $name, $type, $value);
    }

    private function parseReturnStatement(): ?ReturnStatement
    {
        $returnToken = $this->tokenCurrent;
        $this->nextToken();

        $value = $this->parseExpression(Precedence::Lowest);

        return new ReturnStatement($returnToken, $value);
    }

    private function parseGiveStatement(): ?GiveStatement
    {
        $giveToken = $this->tokenCurrent;
        $this->nextToken();

        $value = $this->parseExpression(Precedence::Lowest);

        return new GiveStatement($giveToken, $value);
    }

    public function parseExpressionStatement(): ?ExpressionStatement
    {
        $token = $this->tokenCurrent;
        $expression = $this->parseExpression(Precedence::Lowest);

        if ($this->peekIs(TokenType::Semicolon)) {
            $this->nextToken();
        }

        return new ExpressionStatement($token, $expression);
    }

    private function parseExpression(Precedence $precedence): ?ExpressionInterface
    {
        if (!isset($this->prefixParseFunctions[$this->tokenCurrent->type->value])) {
            while (!$this->currentIs(TokenType::Semicolon) && !$this->currentIs(TokenType::Eof)) {
                $this->nextToken();
            }

            return new class implements ExpressionInterface {
                public function tokenLiteral(): string
                {
                    return "NOT_IMPLEMENTED";
                }

                public function __toString(): string
                {
                    return "NOT_IMPLEMENTED";
                }
            };
        }

        return $this->prefixParseFunctions[$this->tokenCurrent->type->value]();
    }
}
