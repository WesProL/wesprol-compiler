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

    public function __construct(
        private readonly string $input,
    ) {
        $this->readCharacter();
    }

    public function nextToken(): Token
    {
        $this->eatWhitespaces();

        switch ($this->character) {
            case '':
                return $this->createToken(TokenType::Eof, '');
            case '=':
                if ($this->peekCharacter() === '=') {
                    $token = $this->createToken(TokenType::Equal, '==');
                    $this->readCharacter();
                } elseif ($this->peekCharacter() === '>') {
                    $token = $this->createToken(TokenType::MatchArrow, '=>');
                    $this->readCharacter();
                } else {
                    $token = $this->createToken(TokenType::Assignment, '=');
                }
                $this->readCharacter();
                break;
            case '!':
                if ($this->peekCharacter() === '=') {
                    $token = $this->createToken(TokenType::NotEqual, '!=');
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
                    $token = $this->createToken(TokenType::ArrayArrow, '->');
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
                    $token = $this->createToken(TokenType::LogicAnd, '&&');
                } else {
                    $token = $this->createToken(TokenType::Ampersand, '&');
                }
                $this->readCharacter();
                break;
            case '|':
                if ($this->peekCharacter() === '|') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::LogicOr, '||');
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
                    $token = $this->createToken(TokenType::LessOrEqual, '<=');
                } elseif ($this->peekCharacter() === '<') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::ShiftLeft, '<<');
                } else {
                    $token = $this->createToken(TokenType::LessThan, '<');
                }
                $this->readCharacter();
                break;
            case '>':
                if ($this->peekCharacter() === '=') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::GreaterOrEqual, '>=');
                } elseif ($this->peekCharacter() === '>') {
                    $this->readCharacter();
                    $token = $this->createToken(TokenType::ShiftRight, '>>');
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

    private function isLetter(string $character): bool
    {
        return ctype_alpha($character) || $character === '_';
    }

    private function isDigit(string $character): bool
    {
        return ctype_digit($character);
    }
}
