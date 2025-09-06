<?php

namespace RobertWesner\Wesprol\Parser;

enum Precedence: int
{
    case Lowest = 0;
    case NullCoalesce = 1;
    case EqualityCheck = 2;
    case LessGreater = 3;
    case PlusMinus = 4;
    case Bitwise = 5;
    case MultiplyDivideModulo = 6;
    /**
     * . or :: or .{ or ?.
     */
    case Accessor = 7;
    case Prefix = 8;
    case Call = 9;
}
