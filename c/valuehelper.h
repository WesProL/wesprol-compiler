#ifndef _VALUEHELPER_H
#define _VALUEHELPER_H

#include <stdbool.h>
#include "types.h"
#include "types/string.h"
#include "types/null.h"
#include "types/array.h"
#include "types/operators/string.h"
#include "types/operators/char.h"

bool T_Value_equal(struct T_Value a, struct T_Value b) {
    if (a.type != b.type) {
        return false;
    }

    switch (a.type) {
        case TYPE_CHAR:
            return char_operator_infix_equals_double(a, b).valueWrapper.vBool;
        case TYPE_BOOL:
            return a.valueWrapper.vBool == b.valueWrapper.vBool;
        case TYPE_INT:
            return a.valueWrapper.vInt == b.valueWrapper.vInt;
        case TYPE_FLOAT:
            return a.valueWrapper.vFloat == b.valueWrapper.vFloat;
        case TYPE_STRING:
            return string_operator_infix_equals_double(a, b).valueWrapper.vBool;
        case TYPE_ARRAY:
            // TODO
            break;
        case TYPE_ERROR:
            // TODO
            break;
        case TYPE_TYPE:
            // TODO
            break;
        case TYPE_OBJECT:
            // TODO
            break;
        case TYPE_NULL:
            return true;
    }
}

struct T_Value T_Value_clone(struct T_Value val) {
    switch (val.type) {
        case TYPE_BOOL:
            return T_Value_from_bool(val.valueWrapper.vBool);
        case TYPE_INT:
            return T_Value_from_int(val.valueWrapper.vInt);
        case TYPE_FLOAT:
            return T_Value_from_float(val.valueWrapper.vFloat);
        case TYPE_CHAR:
            return T_Value_from_char(val.valueWrapper.vChar);
        case TYPE_STRING:
            return T_Value_from_string(T_StringLiteral_copy(val.valueWrapper.vString));
        case TYPE_ARRAY:
            // TODO
            break;
        case TYPE_ERROR:
            // TODO
            break;
        case TYPE_TYPE:
            // TODO
            break;
        case TYPE_OBJECT:
            // TODO
            break;
        case TYPE_NULL:
            return T_Value_from_null();
    }
}

#endif
