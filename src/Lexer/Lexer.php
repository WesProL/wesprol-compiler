<?php

namespace RobertWesner\Wesprol\Lexer;

use RobertWesner\Wesprol\Token\Token;
use RobertWesner\Wesprol\Token\TokenType;

// TODO: lexer directives

class Lexer
{
    private int $position = 0;
    private int $readingPosition = 0;
    private string $character = '';
    private int $line = 1;
    private int $column = 1;

    /**
     * @var Token[]
     */
    private array $tokenBuffer = [];

    public function __construct(
        private readonly string $input,
    ) {
        $this->readCharacter();
    }

    public function nextToken(): Token
    {
        // empty the buffer created by directives first
        if (count($this->tokenBuffer) > 0) {
            return array_shift($this->tokenBuffer);
        }

        $this->eatWhitespaces();

        switch ($this->character) {
            case '':
                return $this->createToken(TokenType::Eof, '');
            case '=':
                if ($this->peekCharacter() === '=') {
                    $token = $this->createToken(TokenType::EqualsDouble, '==');
                    $this->readCharacter();
                } elseif ($this->peekCharacter() === '>') {
                    $token = $this->createToken(TokenType::EqualsGreaterThanArrow, '=>');
                    $this->readCharacter();
                } else {
                    $token = $this->createToken(TokenType::Equals, '=');
                }
                $this->readCharacter();
                break;
            case '!':
                if ($this->peekCharacter() === '=') {
                    $token = $this->createToken(TokenType::NotEquals, '!=');
                    $this->readCharacter();
                } else {
                    $token = $this->createToken(TokenType::Exclamation, '!');
                }
                $this->readCharacter();
                break;
            case '?':
                $token = $this->createToken(TokenType::Question, '?');
                $this->readCharacter();
                break;
            case '+':
                if ($this->peekCharacter() === '=') {
                    $token = $this->createToken(TokenType::PlusEquals, '+=');
                    $this->readCharacter();
                } else {
                    $token = $this->createToken(TokenType::Plus, '+');
                }
                $this->readCharacter();
                break;
            case '-':
                if ($this->peekCharacter() === '=') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::MinusEquals, '-=');
                } elseif ($this->peekCharacter() === '>') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::MinusGreaterThanArrow, '->');
                } else {
                    $token = $this->createToken(TokenType::Minus, '-');
                }
                $this->readCharacter();
                break;
            case '*':
                if ($this->peekCharacter() === '=') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::AsteriskEquals, '*=');
                } else {
                    $token = $this->createToken(TokenType::Asterisk, '*');
                }
                $this->readCharacter();
                break;
            case '/':
                if ($this->peekCharacter() === '=') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::SlashEquals, '/=');
                } elseif ($this->peekCharacter() === '/') {
                    while ($this->character !== "\n") {
                        $this->readCharacter();
                    }
                    $this->readCharacter();

                    return $this->nextToken();
                } else {
                    $token = $this->createToken(TokenType::Slash, '/');
                }
                $this->readCharacter();
                break;
            case '%':
                $token = $this->createToken(TokenType::Percent, '%');
                $this->readCharacter();
                break;
            case '&':
                if ($this->peekCharacter() === '&') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::AmpersandDouble, '&&');
                } else {
                    $token = $this->createToken(TokenType::Ampersand, '&');
                }
                $this->readCharacter();
                break;
            case '|':
                if ($this->peekCharacter() === '|') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::PipeDouble, '||');
                } else {
                    $token = $this->createToken(TokenType::Pipe, '|');
                }
                $this->readCharacter();
                break;
            case '^':
                $token = $this->createToken(TokenType::Caret, '^');
                $this->readCharacter();
                break;
            case '~':
                $token = $this->createToken(TokenType::Tilde, '~');
                $this->readCharacter();
                break;
            case '.':
                if ($this->peekCharacter() === '.') {
                    $this->readCharacter();
                    if ($this->peekCharacter() === '=') {
                        $this->readCharacter();
                        $token = $this->createToken(TokenType::RangeInclusive, '..=');
                    } else {
                        $token = $this->createToken(TokenType::Range, '..');
                    }
                } else {
                    $token = $this->createToken(TokenType::Dot, '.');
                }
                $this->readCharacter();
                break;
            case ',':
                $token = $this->createToken(TokenType::Comma, ',');
                $this->readCharacter();
                break;
            case ':':
                if ($this->peekCharacter() === ':') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::ColonDouble, '::');
                } else {
                    $token = $this->createToken(TokenType::Colon, ':');
                }
                $this->readCharacter();
                break;
            case ';':
                $token = $this->createToken(TokenType::Semicolon, ';');
                $this->readCharacter();
                break;
            case '(':
                $token = $this->createToken(TokenType::ParenthesisLeft, '(');
                $this->readCharacter();
                break;
            case ')':
                $token = $this->createToken(TokenType::ParenthesisRight, ')');
                $this->readCharacter();
                break;
            case '{':
                $token = $this->createToken(TokenType::BraceLeft, '{');
                $this->readCharacter();
                break;
            case '}':
                $token = $this->createToken(TokenType::BraceRight, '}');
                $this->readCharacter();
                break;
            case '[':
                $token = $this->createToken(TokenType::SquareBracketLeft, '[');
                $this->readCharacter();
                break;
            case ']':
                $token = $this->createToken(TokenType::SquareBracketRight, ']');
                $this->readCharacter();
                break;
            case '<':
                if ($this->peekCharacter() === '=') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::LessThanEquals, '<=');
                } elseif ($this->peekCharacter() === '<') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::LessThanDouble, '<<');
                } else {
                    $token = $this->createToken(TokenType::LessThan, '<');
                }
                $this->readCharacter();
                break;
            case '>':
                if ($this->peekCharacter() === '=') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::GreaterThanEquals, '>=');
                } elseif ($this->peekCharacter() === '>') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::GreaterThanDouble, '>>');
                } else {
                    $token = $this->createToken(TokenType::GreaterThan, '>');
                }
                $this->readCharacter();
                break;
            case '\\':
                $namespace = '';
                while ($this->isLetter($this->character) || $this->character === '\\') {
                    $namespace .= $this->character;
                    $this->readCharacter();
                }
                $token = $this->createToken(TokenType::NamespaceLiteral, $namespace);
                break;
            case '\'':
                $token = $this->createToken(TokenType::CharacterLiteral, $this->readCharacterSequence());
                break;
            case '"':
                $token = $this->createToken(TokenType::StringLiteral, $this->readStringSequence());
                break;
            case '$':
                $this->readCharacter();
                $directive = '$' . $this->readIdentifier();
                $this->tokenBuffer = match ($directive) {
                    '$run' => $this->readDirectiveRun(),
                    '$pass' => $this->readDirectivePass(),
                    '$get' => $this->readDirectiveGet(),
                    default => [$this->createToken(TokenType::Illegal, $directive)],
                };

                $token = array_shift($this->tokenBuffer);
                break;
            default:
                if ($this->isLetter($this->character)) {
                    $identifier = $this->readIdentifier();

                    return $this->createToken(TokenType::lookupIdentifier($identifier), $identifier);
                }

                if ($this->isDigit($this->character)) {
                    $number = $this->readNumber();
                    if (str_contains($number, '.')) {
                        return $this->createToken(TokenType::TFloat, $number);
                    } else {
                        return $this->createToken(TokenType::Integer, $number);
                    }
                }

                $token = $this->createToken(TokenType::Illegal, $this->character);
                $this->readCharacter();
        }

        return $token;
    }

    private function readCharacter(): void
    {
        if ($this->readingPosition > mb_strlen($this->input)) {
            $this->character = '';
        } else {
            $this->character = mb_substr($this->input, $this->readingPosition, 1);
        }

        $this->position = $this->readingPosition;
        $this->readingPosition++;

        if ($this->character === "\n") {
            $this->column = 0;
		    $this->line++;
        } elseif ($this->character != "\r") {
            $this->column++;
        }
    }

    private function peekCharacter(): string
    {
        if ($this->readingPosition > mb_strlen($this->input)) {
            return '';
        } else {
            return mb_substr($this->input, $this->readingPosition, 1);
        }
    }

    private function createToken(TokenType $type, string $literal): Token
    {
        return new Token($type, $literal, $this->line, $this->column);
    }

    private function eatWhitespaces(): void
    {
        while (in_array($this->character, [" ", "\t", "\n", "\r"])) {
            $this->readCharacter();
        }
    }

    private function readIdentifier(): string
    {
        $position = $this->position;
        $length = 0;

        while ($this->isLetter($this->character) || $this->isDigit($this->character)) {
            $this->readCharacter();
            $length++;
        }

        return mb_substr($this->input, $position, $length);
    }

    private function readNumber(): string
    {
        $position = $this->position;
        $length = 0;

        while ($this->isDigit($this->character)) {
            $this->readCharacter();
            $length++;
        }

        // second condition is important to not fail with ranges ('..', '..=')
        if ($this->character === '.' && $this->isDigit($this->peekCharacter())) {
            $this->readCharacter();
            $length++;

            while ($this->isDigit($this->character)) {
                $this->readCharacter();
                $length++;
            }
        }

        return mb_substr($this->input, $position, $length);
    }

    private function readCharacterSequence(): string
    {
        $literal = $this->character;
        $this->readCharacter();
        while ($this->character !== '\'') {
            if ($this->character === '\\') {
                $literal .= $this->character;
                $this->readCharacter();
            }

            $literal .= $this->character;
            $this->readCharacter();
        }
        $literal .= $this->character;
        $this->readCharacter();

        return $literal;
    }

    private function readStringSequence(): string
    {
        $literal = $this->character;
        $this->readCharacter();
        while ($this->character !== '"') {
            if ($this->character === '\\') {
                $literal .= $this->character;
                $this->readCharacter();
            }

            $literal .= $this->character;
            $this->readCharacter();
        }
        $literal .= $this->character;
        $this->readCharacter();

        return $literal;
    }

    /**
     * @return Token[]
     */
    private function readDirectiveRun(): array
    {
        $source = "";
        while (true) {
            if (in_array($this->character, [' ', "\t", "\r", "\n"])) {
                if (!str_ends_with($source, ' ')) {
                    $source .= " ";
                }
                $this->eatWhitespaces();
            }

            if ($this->character === '') {
                var_dump($source);
                return $this->createDirectiveErrorTokens('RUN', 0);
            }

            if ($this->character === '/') {
                switch ($this->peekCharacter()) {
                    case '/':
                        $this->readCharacter();
                        $this->readCharacter();
                        $this->readDirectiveRunCUntil("\n");
                        $this->readCharacter();
                        break;
                    case '*':
                        $this->readCharacter();
                        $this->readCharacter();
                        $this->readDirectiveRunCUntil('*', '/');
                        $this->readCharacter();
                        $this->readCharacter();
                        break;
                }
            }

            if ($this->character === '$') {
                $this->readCharacter();
                if (($this->readIdentifier()) === 'end') {
                    break;
                } else {
                    return $this->createDirectiveErrorTokens('RUN', 1);
                }
            }

            if ($this->character === '\'') {
                $this->readCharacter();
                $source .= '\'' . $this->readDirectiveRunCLiteralWithEscapes('\'') . '\'';;
                $this->readCharacter();
            }

            if ($this->character === '"') {
                $this->readCharacter();
                $source .= '"' . $this->readDirectiveRunCLiteralWithEscapes('"') . '"';
                $this->readCharacter();
            }

            if (in_array($this->character, [' ', "\t", "\r", "\n"])) {
                if (!str_ends_with($source, ' ')) {
                    $source .= " ";
                }
                $this->eatWhitespaces();
            } else {
                $source .= $this->character;
                $this->readCharacter();
            }
        }

        return [
            $this->createToken(TokenType::LDRun, '$run'),
            $this->createToken(TokenType::LDRunCode, $source),
            $this->createToken(TokenType::LDEnd, '$end'),
        ];
    }

    /**
     * @return Token[]
     */
    private function readDirectivePass(): array
    {
        $parts = [];
        do {
            $this->eatWhitespaces();

            $part = '';
            while (true) {
                if (in_array($this->character, [' ', "\t", "\r", "\n", ';', ',', ''])) {
                    break;
                }

                $part .= $this->character;
                $this->readCharacter();
            }

            $parts[] = $part;

            if ($this->character === ',') {
                $parts[] = ',';
                $this->readCharacter();
            }
        } while ($part !== '$end' && $part !== '');

        $chunks = [];
        $chunk = [];
        while ($part = array_shift($parts)) {
            if ($part === ',') {
                $chunks[] = $chunk;
                $chunk = [];
                continue;
            }

            if ($part === '$end') {
                break;
            }

            $chunk[] = $part;
        }
        $chunks[] = $chunk;

        if (array_any($chunks, fn($chunk) => count($chunk) !== 3)) {
            return $this->createDirectiveErrorTokens('PASS', 0);
        }

        if (array_any($chunks, fn($chunk) => $chunk[1] !== 'as')) {
            return $this->createDirectiveErrorTokens('PASS', 1);
        }

        return [
            $this->createToken(TokenType::LDPass, '$pass'),
            ...array_merge(
                ...array_map(
                    fn (array $chunk) => [
                        $this->createToken(TokenType::LDPassSource, $chunk[0]),
                        $this->createToken(TokenType::LDPassDestination, $chunk[2]),
                    ],
                    $chunks,
                ),
            ),
            $this->createToken(TokenType::LDEnd, '$end'),
        ];
    }

    /**
     * @return Token[]
     */
    private function readDirectiveGet(): array
    {
        $parts = [];
        do {
            $this->eatWhitespaces();

            $part = '';
            while (true) {
                if (in_array($this->character, [' ', "\t", "\r", "\n", ';', ''])) {
                    break;
                }

                $part .= $this->character;
                $this->readCharacter();
            }

            $parts[] = $part;
        } while ($part !== '$end' && $part !== '');

        if (count($parts) !== 4) {
            return $this->createDirectiveErrorTokens('GET', 0);
        }

        if ($parts[1] !== 'as') {
            return $this->createDirectiveErrorTokens('GET', 1);
        }

        if (!ctype_alnum($parts[0])) {
            return $this->createDirectiveErrorTokens('GET', 2);
        }

        if (!in_array(TokenType::lookupIdentifier($parts[2]), TokenType::TYPES)) {
            return $this->createDirectiveErrorTokens('GET', 3);
        }

        return [
            $this->createToken(TokenType::LDGet, '$get'),
            $this->createToken(TokenType::LDGetVariable, $parts[0]),
            $this->createToken(TokenType::LDGetType, $parts[2]),
            $this->createToken(TokenType::LDEnd, '$end'),
        ];
    }

    private function readDirectiveRunCUntil(string $endCharacter, ?string $endPeek = null): string
    {
        $result = "";
        while (true) {
            if (
                ($endPeek === null && $this->character === $endCharacter)
                || ($this->character === $endCharacter && $this->peekCharacter() === $endPeek)
                || $this->character === ''
            ) {
                return $result;
            }

            $result .= $this->character;
            $this->readCharacter();
        }
    }

    private function readDirectiveRunCLiteralWithEscapes(string $endCharacter): string
    {
        $result = "";
        while (true) {
            if ($this->character === $endCharacter || $this->character === '') {
                return $result;
            }

            $result .= $this->character;
            $this->readCharacter();

            if ($this->character === '\\') {
                $result .= $this->character;
                $this->readCharacter();
            }
        }
    }

    private function createDirectiveErrorTokens(string $name, int $expectation): array
    {
        return [
            $this->createToken(
                TokenType::Illegal,
                'LEXER_DIRECTIVE_' . $name . '_ERROR_EXPECTATION_' . $expectation . '_FAILED',
            ),
        ];
    }

    private function isLetter(string $character): bool
    {
        return ctype_alpha($character) || $character === '_';
    }

    private function isDigit(string $character): bool
    {
        return ctype_digit($character);
    }
}
