<?php

namespace RobertWesner\Wesprol\Token;

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
    case Question = '?';
    case Plus = '+';
    case Minus = '-';
    case Asterisk = '*';
    case Slash = '/';
    case PlusEquals = '+=';
    case MinusEquals = '-=';
    case AsteriskEquals = '*=';
    case SlashEquals = '/=';
    case Ampersand = '&';
    case Pipe = '|';
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
    case MatchArrow = '=>';
    case ArrayArrow = '->';
    case LogicAnd = '&&';
    case LogicOr = '||';

    // keywords
    case True = 'true';
    case False = 'false';
    case Null = 'null';
    case Default = 'default';
    case If = 'if';
    case Else = 'else';
    case ElseIf = 'elseif';
    case Match = 'match';
    case While = 'while';
    case Do = 'do';
    case Loop = 'loop';
    case For = 'for';
    case In = 'in';
    case Break = 'break';
    case Continue = 'continue';
    case Return = 'return';
    case Self = 'self';
    case Let = 'let';
    case Function = 'function';
    case Private = 'private';
    case Protected = 'protected';
    case Public = 'public';
    case Static = 'static';
    case ClassDef = 'class';
    case TraitDef = 'trait';
    case ErrorDef = 'error';
    case New = 'new';
    case Delete = 'delete';
    case Use = 'use';
    case As = 'as';
    case Namespace = 'namespace';
    case Throw = 'throw';
    case Catch = 'catch';
    case InstanceOf = 'instanceof';

    // type-keywords
    case TBool = 'bool';
    case TInt = 'int';
    case TFloat = 'float';
    case TChar = 'char';
    case TString = 'string';
    case TArray = 'array';
    case TVoid = 'void';
    case TNever = 'never';
    case TType = 'type';

    public static function lookupIdentifier(string $identifier): self
    {
        return match ($identifier) {
            'true' => self::True,
            'false' => self::False,
            'null' => self::Null,
            'default' => self::Default,
            'if' => self::If,
            'else' => self::Else,
            'elseif' => self::ElseIf,
            'match' => self::Match,
            'while' => self::While,
            'do' => self::Do,
            'loop' => self::Loop,
            'for' => self::For,
            'in' => self::In,
            'break' => self::Break,
            'continue' => self::Continue,
            'return' => self::Return,
            'self' => self::Self,
            'let' => self::Let,
            'function' => self::Function,
            'private' => self::Private,
            'protected' => self::Protected,
            'public' => self::Public,
            'static' => self::Static,
            'class' => self::ClassDef,
            'trait' => self::TraitDef,
            'error' => self::ErrorDef,
            'new' => self::New,
            'delete' => self::Delete,
            'use' => self::Use,
            'as' => self::As,
            'namespace' => self::Namespace,
            'throw' => self::Throw,
            'catch' => self::Catch,
            'instanceof' => self::InstanceOf,
            'bool' => self::TBool,
            'int' => self::TInt,
            'float' => self::TFloat,
            'char' => self::TChar,
            'string' => self::TString,
            'array' => self::TArray,
            'void' => self::TVoid,
            'never' => self::TNever,
            'type' => self::TType,
            default => self::Identifier,
        };
    }
}
