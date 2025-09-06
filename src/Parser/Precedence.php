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
    /**
     * . or :: or .{ or ?.
     */
    case Accessor = 9;
    case Prefix = 10;
    case Call = 11;
}
