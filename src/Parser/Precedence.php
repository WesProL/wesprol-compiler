<?php

namespace RobertWesner\Wesprol\Parser;

enum Precedence: int
{
    case Lowest = 0;
    case LogicOrAnd = 1;
    case NullCoalesce = 2;
    case EqualityCheck = 3;
    case LessGreater = 4;
    case Range = 5;
    case PlusMinus = 6;
    case Bitwise = 7;
    case MultiplyDivideModulo = 8;
    case Prefix = 9;
    /**
     * . or :: or .{ or ?.
     */
    case Accessor = 10;
    case Reference = 11;
    case Call = 12;
}
