<?php

namespace RobertWesner\Wesprol\Tests\Lexer;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RobertWesner\Wesprol\Lexer\Lexer;
use RobertWesner\Wesprol\Token\TokenType;

class LexerTest extends TestCase
{
    public static function dataProvider(): Generator
    {
        yield 'let #1' => [
            'let foo int = (1 + 2) * 3;',
            [
                [TokenType::Let, 'let'],
                [TokenType::Identifier, 'foo'],
                [TokenType::Int, 'int'],
                [TokenType::Assignment, '='],
                [TokenType::ParenthesisLeft, '('],
                [TokenType::Integer, '1'],
                [TokenType::Plus, '+'],
                [TokenType::Integer, '2'],
                [TokenType::ParenthesisRight, ')'],
                [TokenType::Asterisk, '*'],
                [TokenType::Integer, '3'],
                [TokenType::Semicolon, ';'],
            ],
        ];

        yield 'namespace' => [
            'use \Std\Format;',
            [
                [TokenType::Use, 'use'],
                [TokenType::Namespace, '\Std\Format'],
                [TokenType::Semicolon, ';'],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function test(string $input, array $expected): void
    {
        $tokens = [];
        $lexer = new Lexer($input);
        for ($token = $lexer->nextToken(); $token->type !== TokenType::Eof; $token = $lexer->nextToken()) {
            $tokens[] = [$token->type, $token->literal];
        }

        self::assertSame($expected, $tokens);
    }
}
