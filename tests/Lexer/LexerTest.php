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
                // this is our namespace
                namespace \App;
                
                // we include the format class from the StdLib
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

        yield 'if with many conditions' => [
            <<<'EOF'
                if (foo >= 1 && bar < 10) || faz == baz {
                    // ...
                }
                EOF,
            [
                [TokenType::If, 'if'],
                [TokenType::ParenthesisLeft, '('],
                [TokenType::Identifier, 'foo'],
                [TokenType::GreaterOrEqual, '>='],
                [TokenType::Integer, '1'],
                [TokenType::LogicAnd, '&&'],
                [TokenType::Identifier, 'bar'],
                [TokenType::LessThan, '<'],
                [TokenType::Integer, '10'],
                [TokenType::ParenthesisRight, ')'],
                [TokenType::LogicOr, '||'],
                [TokenType::Identifier, 'faz'],
                [TokenType::Equal, '=='],
                [TokenType::Identifier, 'baz'],
                [TokenType::BraceLeft, '{'],
                [TokenType::BraceRight, '}'],
            ],
        ];

        yield 'array unknown' => [
            <<<'EOF'
                array[?]
                EOF,
            [
                [TokenType::TArray, 'array'],
                [TokenType::SquareBracketLeft, '['],
                [TokenType::Question, '?'],
                [TokenType::SquareBracketRight, ']'],
            ],
        ];

        yield 'array of strings' => [
            <<<'EOF'
                array[string]
                EOF,
            [
                [TokenType::TArray, 'array'],
                [TokenType::SquareBracketLeft, '['],
                [TokenType::TString, 'string'],
                [TokenType::SquareBracketRight, ']'],
            ],
        ];

        /**
         * Example:
         *  [
         *      "Foo",
         *      "Bar",
         *      [1, 2, 3],
         *  ]
         */
        yield 'array of (strings OR arrays of ints)' => [
            <<<'EOF'
                array[string|array[int]]
                EOF,
            [
                [TokenType::TArray, 'array'],
                [TokenType::SquareBracketLeft, '['],
                [TokenType::TString, 'string'],
                [TokenType::Pipe, '|'],
                [TokenType::TArray, 'array'],
                [TokenType::SquareBracketLeft, '['],
                [TokenType::TInt, 'int'],
                [TokenType::SquareBracketRight, ']'],
                [TokenType::SquareBracketRight, ']'],
            ],
        ];

        yield 'range' => [
            <<<'EOF'
                for i in 0..100 {
                    // 0 to 99
                }
                EOF,
            [
                [TokenType::For, 'for'],
                [TokenType::Identifier, 'i'],
                [TokenType::In, 'in'],
                [TokenType::Integer, '0'],
                [TokenType::Range, '..'],
                [TokenType::Integer, '100'],
                [TokenType::BraceLeft, '{'],
                [TokenType::BraceRight, '}'],
            ],
        ];

        yield 'range inclusive' => [
            <<<'EOF'
                for i in 0..=100 {
                    // 0 to 100
                }
                EOF,
            [
                [TokenType::For, 'for'],
                [TokenType::Identifier, 'i'],
                [TokenType::In, 'in'],
                [TokenType::Integer, '0'],
                [TokenType::RangeInclusive, '..='],
                [TokenType::Integer, '100'],
                [TokenType::BraceLeft, '{'],
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
