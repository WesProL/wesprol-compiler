<?php

namespace RobertWesner\Wesprol\Tests\Parser;

use Generator;
use mysql_xdevapi\Expression;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RobertWesner\Wesprol\Ast\Expression\Identifier;
use RobertWesner\Wesprol\Ast\ExpressionInterface;
use RobertWesner\Wesprol\Ast\Statement\ExpressionStatement;
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
            let foobar string = 838383;
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
            return 838383;
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
            give 838383;
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

    public function testIdentifierExpression(): void
    {
        $input = 'foobar;';

        $parser = new Parser(new Lexer($input));
        $program = $parser->parse();
        self::assertNoParserErrors($parser);
        self::assertCount(1, $program->statements);

        $actual = $program->statements[0];
        self::assertInstanceOf(ExpressionStatement::class, $actual);
        $expression = $actual->expression;
        self::assertInstanceOf(Identifier::class, $expression);
        self::assertSame("foobar", $expression->value);
    }

    public static function precedenceProvider(): Generator
    {
        yield ["((-a) * b)", "-a * b"];
        yield ["(!(-a))", "!-a"];
        yield ["((a + b) + c)", "a + b + c"];
        yield ["((a + b) - c)", "a + b - c"];
        yield ["((a * b) * c)", "a * b * c"];
        yield ["((a * b) / c)", "a * b / c"];
        yield ["(a + (b * c))", "a + b * c"];
        yield ["(a + (b / c))", "a + b / c"];
        yield ["(((a + (b * c)) + (d / e)) - f)", "a + b * c + d / e - f"];
        yield ["((5 > 4) == (3 < 4))", "5 > 4 == 3 < 4"];
    }

    #[DataProvider('precedenceProvider')]
    public function testPrecedence(string $expected, string $input): void
    {
        $parser = new Parser(new Lexer($input));
        $program = $parser->parse();
        self::assertNoParserErrors($parser);

        self::assertSame($expected, (string)$program);
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
