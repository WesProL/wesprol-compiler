<?php

namespace RobertWesner\Wesprol\Token;

// TODO: break, continue, return, @Attributes or #[Attributes]?

enum TokenType: string
{
    case Illegal = 'illegal';
    case Eof = 'EOF';
    case Identifier = 'identifier';
    case Integer = 'integer';
    case Decimal = 'decimal';
    case CharacterLiteral = 'character_literal';
    case StringLiteral = 'string_literal';
    case NamespaceLiteral = 'namespace_literal';
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
    case Namespace = 'namespace';

    // type-keywords
    case TBool = 'bool';
    case TInt = 'int';
    case TFloat = 'float';
    case TChar = 'char';
    case TString = 'string';
    case TVoid = 'void';
    case TNever = 'never';

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
            'namespace' => self::Namespace,
            'bool' => self::TBool,
            'int' => self::TInt,
            'float' => self::TFloat,
            'char' => self::TChar,
            'string' => self::TString,
            'void' => self::TVoid,
            'never' => self::TNever,
            default => self::Identifier,
        };
    }
}
