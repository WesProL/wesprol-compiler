<?php

namespace RobertWesner\Wesprol\Parser;

use RobertWesner\Wesprol\Ast\Expression\FloatLiteral;
use RobertWesner\Wesprol\Ast\Expression\Identifier;
use RobertWesner\Wesprol\Ast\Expression\InfixExpression;
use RobertWesner\Wesprol\Ast\Expression\IntLiteral;
use RobertWesner\Wesprol\Ast\Expression\PrefixExpression;
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

    /** @var array<string, Precedence> */
    private static $precedences = [
        TokenType::EqualsDouble->value => Precedence::EqualityCheck,
        TokenType::ExclamationEquals->value => Precedence::EqualityCheck,
        TokenType::LessThan->value => Precedence::LessGreater,
        TokenType::GreaterThan->value => Precedence::LessGreater,
        TokenType::LessThanEquals->value => Precedence::LessGreater,
        TokenType::GreaterThanEquals->value => Precedence::LessGreater,
        TokenType::Plus->value => Precedence::PlusMinus,
        TokenType::Minus->value => Precedence::PlusMinus,
        TokenType::Asterisk->value => Precedence::MultiplyDivideModulo,
        TokenType::Slash->value => Precedence::MultiplyDivideModulo,
        TokenType::Percent->value => Precedence::MultiplyDivideModulo,
        TokenType::Ampersand->value => Precedence::Bitwise,
        TokenType::Pipe->value => Precedence::Bitwise,
        TokenType::Caret->value => Precedence::Bitwise,
        TokenType::LessThanDouble->value => Precedence::Bitwise,
        TokenType::GreaterThanDouble->value => Precedence::Bitwise,
        TokenType::AmpersandDouble->value => Precedence::LogicOrAnd,
        TokenType::PipeDouble->value => Precedence::LogicOrAnd,
        TokenType::Range->value => Precedence::Range,
        TokenType::RangeInclusive->value => Precedence::Range,
        TokenType::QuestionDouble->value => Precedence::NullCoalesce,
        TokenType::Dot->value => Precedence::Accessor,
        TokenType::QuestionDot->value => Precedence::Accessor,
        TokenType::DotBraceLeft->value => Precedence::Accessor,
        TokenType::ColonDouble->value => Precedence::Accessor,
    ];

    /**
     * @noinspection DuplicatedCode
     */
    public function __construct(
        private readonly Lexer $lexer,
    ) {
        // populate current and peek
        $this->nextToken();
        $this->nextToken();

        $this->registerPrefix(TokenType::Identifier, function (): ExpressionInterface {
            return new Identifier($this->tokenCurrent, $this->tokenCurrent->literal);
        });
        $this->registerPrefix(TokenType::Integer, function (): ExpressionInterface {
            return new IntLiteral($this->tokenCurrent, $this->tokenCurrent->literal);
        });
        $this->registerPrefix(TokenType::FloatingPointNumber, function (): ExpressionInterface {
            return new FloatLiteral($this->tokenCurrent, $this->tokenCurrent->literal);
        });
        $this->registerPrefix(TokenType::Exclamation, $this->parsePrefixExpression(...));
        $this->registerPrefix(TokenType::Minus, $this->parsePrefixExpression(...));
        $this->registerPrefix(TokenType::Ampersand, $this->parsePrefixExpression(...));
        $this->registerPrefix(TokenType::Asterisk, $this->parsePrefixExpression(...));
        $this->registerPrefix(TokenType::Tilde, $this->parsePrefixExpression(...));

        $this->registerInfix(TokenType::EqualsDouble, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::ExclamationEquals, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::LessThan, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::GreaterThan, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::LessThanEquals, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::GreaterThanEquals, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Plus, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Minus, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Asterisk, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Slash, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Percent, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Ampersand, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Pipe, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Caret, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::LessThanDouble, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::GreaterThanDouble, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::AmpersandDouble, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::PipeDouble, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Range, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::RangeInclusive, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::QuestionDouble, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::Dot, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::QuestionDot, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::DotBraceLeft, $this->parseInfixExpression(...));
        $this->registerInfix(TokenType::ColonDouble, $this->parseInfixExpression(...));
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

    private function currentPrecedence(): Precedence
    {
        if (isset(self::$precedences[$this->tokenCurrent->type->value])) {
            return self::$precedences[$this->tokenCurrent->type->value];
        }

        return Precedence::Lowest;
    }

    private function peekPrecedence(): Precedence
    {
        if (isset(self::$precedences[$this->tokenPeek->type->value])) {
            return self::$precedences[$this->tokenPeek->type->value];
        }

        return Precedence::Lowest;
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
        return match ($this->tokenCurrent->type) {
            TokenType::Let => $this->parseLetStatement(),
            TokenType::Return => $this->parseReturnStatement(),
            TokenType::Give => $this->parseGiveStatement(),
            default => $this->parseExpressionStatement(),
        };
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
            $this->errors[] = new ParserError(
                $this->tokenPeek->line,
                $this->tokenPeek->column,
                sprintf(
                    'No prefix parse function for "%s" on line %d column %d..',
                    $this->tokenCurrent->type->value,
                    $this->tokenCurrent->line,
                    $this->tokenCurrent->column,
                ),
            );

            return null;
        }
        $leftExpression = $this->prefixParseFunctions[$this->tokenCurrent->type->value]();

        while (
            !$this->peekIs(TokenType::Semicolon)
            && !$this->peekIs(TokenType::Eof)
            && $precedence->value < $this->peekPrecedence()->value
        ) {
            if (!isset($this->infixParseFunctions[$this->tokenPeek->type->value])) {
                return $leftExpression;
            }

            $infix = $this->infixParseFunctions[$this->tokenPeek->type->value];
            $this->nextToken();

            $leftExpression = $infix($leftExpression);
        }

        return $leftExpression;
    }

    private function parsePrefixExpression(): ExpressionInterface
    {
        $token = $this->tokenCurrent;
        $this->nextToken();
        $right = $this->parseExpression(Precedence::Prefix);

        return new PrefixExpression($token, $token->type, $right);
    }

    private function parseInfixExpression(ExpressionInterface $left): ExpressionInterface
    {
        $token = $this->tokenCurrent;

        $precedence = $this->currentPrecedence();
        $this->nextToken();
        $right = $this->parseExpression($precedence);

        return new InfixExpression($token, $left, $token->type, $right);
    }
}
