<?php

namespace RobertWesner\Wesprol\Token;

enum TokenType: string
{
    // TODO: comment tokens :)
    case Illegal = 'illegal';
    case Eof = 'EOF';
    case Identifier = 'identifier';
    case Integer = 'integer';
    case Decimal = 'decimal';
    case Namespace = 'namespace';
    case Assignment = '=';
    case Exclamation = '!';
    case Plus = '+';
    case Minus = '-';
    case Asterisk = '*';
    case Slash = '/';
    case PlusEquals = '+=';
    case MinusEquals = '-=';
    case AsteriskEquals = '*=';
    case SlashEquals = '/=';
    case Ampersand = '&';
    case Backslash = '\\';
    case Dot = '.';
    case Comma = ',';
    case Colon = ':';
    case ColonDouble = '::';
    case Semicolon = ';';
    case ParenthesisLeft = '(';
    case ParenthesisRight = ')';
    case BraceLeft = '{';
    case BraceRight = '}';
    case SquareBracketLeft = '[';
    case SquareBracketRight = ']';
    case LessThan = '<';
    case GreaterThan = '>';
    case LessOrEqual = '<=';
    case GreaterOrEqual = '>=';
    case Equal = '==';
    case NotEqual = '!=';
    case Range = '..';
    case RangeInclusive = '..=';

    // keywords
    case True = 'true';
    case False = 'false';
    case Null = 'null';
    case If = 'if';
    case Else = 'else';
    case ElseIf = 'elseif';
    case Match = 'match';
    case While = 'while';
    case Do = 'do';
    case Loop = 'loop';
    case For = 'for';
    case In = 'in';
    case Let = 'let';
    case Function = 'function';
    case Private = 'private';
    case Protected = 'protected';
    case Public = 'public';
    case Static = 'static';
    case ClassDef = 'class';
    case New = 'new';
    case Delete = 'delete';
    case Use = 'use';

    // type-keywords
    case Bool = 'bool';
    case Int = 'int';
    case Float = 'float';
    case Char = 'char';
    case String = 'string';
    case Void = 'void';
    case Never = 'never';

    public static function lookupIdentifier(string $identifier): self
    {
        return match ($identifier) {
            'true' => self::True,
            'false' => self::False,
            'null' => self::Null,
            'if' => self::If,
            'else' => self::Else,
            'elseif' => self::ElseIf,
            'match' => self::Match,
            'while' => self::While,
            'do' => self::Do,
            'loop' => self::Loop,
            'for' => self::For,
            'in' => self::In,
            'let' => self::Let,
            'function' => self::Function,
            'private' => self::Private,
            'protected' => self::Protected,
            'public' => self::Public,
            'static' => self::Static,
            'class' => self::ClassDef,
            'new' => self::New,
            'delete' => self::Delete,
            'use' => self::Use,
            'bool' => self::Bool,
            'int' => self::Int,
            'float' => self::Float,
            'char' => self::Char,
            'string' => self::String,
            'void' => self::Void,
            'never' => self::Never,
            default => self::Identifier,
        };
    }
}
