#include "../../types.h"
#include "../string.h"

#ifndef _TYPES_OPERATORS_CHAR_H
#define _TYPES_OPERATORS_CHAR_H

struct T_Value char_operator_infix_equals_double(struct T_Value a, struct T_Value b) {
    return T_Value_from_bool(
        T_CharRaw_equal(
            a.valueWrapper.vChar.character,
            b.valueWrapper.vChar.character
        )
    );
}

#endif
