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
        yield 'let' => [
            'let foo int = (1 + 2) * 3;',
            [
                [TokenType::Let, 'let'],
                [TokenType::Identifier, 'foo'],
                [TokenType::TInt, 'int'],
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
            'use \Standard\Format;',
            [
                [TokenType::Use, 'use'],
                [TokenType::NamespaceLiteral, '\Standard\Format'],
                [TokenType::Semicolon, ';'],
            ],
        ];

        yield 'hello world' => [
            <<<'EOF'
                namespace \App;
                
                use \Standard\Format;
                
                class Program {
                    public static function main() void {    
                        Format::println("Hello World!");
                    }
                }
                EOF,
            [
                [TokenType::Namespace, 'namespace'],
                [TokenType::NamespaceLiteral, '\App'],
                [TokenType::Semicolon, ';'],
                [TokenType::Use, 'use'],
                [TokenType::NamespaceLiteral, '\Standard\Format'],
                [TokenType::Semicolon, ';'],
                [TokenType::ClassDef, 'class'],
                [TokenType::Identifier, 'Program'],
                [TokenType::BraceLeft, '{'],
                [TokenType::Public, 'public'],
                [TokenType::Static, 'static'],
                [TokenType::Function, 'function'],
                [TokenType::Identifier, 'main'],
                [TokenType::ParenthesisLeft, '('],
                [TokenType::ParenthesisRight, ')'],
                [TokenType::TVoid, 'void'],
                [TokenType::BraceLeft, '{'],
                [TokenType::Identifier, 'Format'],
                [TokenType::ColonDouble, '::'],
                [TokenType::Identifier, 'println'],
                [TokenType::ParenthesisLeft, '('],
                [TokenType::StringLiteral, '"Hello World!"'],
                [TokenType::ParenthesisRight, ')'],
                [TokenType::Semicolon, ';'],
                [TokenType::BraceRight, '}'],
                [TokenType::BraceRight, '}'],
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
