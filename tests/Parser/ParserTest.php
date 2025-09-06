<?php

namespace RobertWesner\Wesprol\Tests\Parser;

use PHPUnit\Framework\TestCase;
use RobertWesner\Wesprol\Ast\Statement\LetStatement;
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
        self::assertCount(3, $program->statements);

        $expected = [
            ["x", TokenType::TInt],
            ["y", TokenType::TInt],
            ["foobar", TokenType::TString],
        ];

        foreach ($expected as $i => [$name, $type]) {
            $actual = $program->statements[$i];
            self::assertInstanceOf(LetStatement::class, $actual);
            self::assertSame($name, $actual->name->value);
            self::assertSame($type, $actual->type->token->type);
        }
    }
}
