<?php

namespace RobertWesner\Wesprol\Tests\Parser;

use PHPUnit\Framework\TestCase;
use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Ast\Statement\GiveStatement;
use RobertWesner\Wesprol\Ast\Statement\LetStatement;
use RobertWesner\Wesprol\Ast\Statement\ReturnStatement;
use RobertWesner\Wesprol\Lexer\Lexer;
use RobertWesner\Wesprol\Parser\Parser;
use RobertWesner\Wesprol\Token\TokenType;

class ParserTest extends TestCase
{
    public function testLetStatements(): void
    {
        $input = <<<EOF
            let x int = 5;
            let y int = 10;
            let foobar string = "test";
            EOF;

        $parser = new Parser(new Lexer($input));
        $program = $parser->parse();
        self::assertNoParserErrors($parser);
        self::assertCount(3, $program->statements);

        $expected = [
            ["x", TokenType::TInt],
            ["y", TokenType::TInt],
            ["foobar", TokenType::TString],
        ];

        foreach ($expected as $i => [$name, $type]) {
            $actual = $program->statements[$i];
            self::assertInstanceOf(LetStatement::class, $actual);
            self::assertSame(TokenType::Let, $actual->token->type);
            self::assertSame("let", $actual->token->literal);
            self::assertSame($name, $actual->name->value);
            self::assertSame($type, $actual->type->token->type);
            self::assertInstanceOf(ExpressionInterface::class, $actual->value);
            // TODO: check expression
        }
    }

    public function testReturnStatements(): void
    {
        $input = <<<EOF
            return 5;
            return "test";
            return 1 + 2;
            EOF;

        $parser = new Parser(new Lexer($input));
        $program = $parser->parse();
        self::assertNoParserErrors($parser);
        self::assertCount(3, $program->statements);

        foreach ($program->statements as $actual) {
            self::assertInstanceOf(ReturnStatement::class, $actual);
            self::assertSame(TokenType::Return, $actual->token->type);
            self::assertSame("return", $actual->token->literal);
            self::assertInstanceOf(ExpressionInterface::class, $actual->value);
            // TODO: check expression
        }
    }

    public function testGiveStatements(): void
    {
        $input = <<<EOF
            give 5;
            give "test";
            give 1 + 2;
            EOF;

        $parser = new Parser(new Lexer($input));
        $program = $parser->parse();
        self::assertNoParserErrors($parser);
        self::assertCount(3, $program->statements);

        foreach ($program->statements as $actual) {
            self::assertInstanceOf(GiveStatement::class, $actual);
            self::assertSame(TokenType::Give, $actual->token->type);
            self::assertSame("give", $actual->token->literal);
            self::assertInstanceOf(ExpressionInterface::class, $actual->value);
            // TODO: check expression
        }
    }

    private static function assertNoParserErrors(Parser $parser): void
    {
        if (count($parser->getErrors()) === 0) {
            return;
        }

        foreach ($parser->getErrors() as $error) {
            echo $error->message;
            self::fail();
        }
    }
}
