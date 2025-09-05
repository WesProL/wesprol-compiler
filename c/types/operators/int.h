#include "../../types.h"

#ifndef _TYPES_OPERATORS_INT_H
#define _TYPES_OPERATORS_INT_H

struct T_Value int_operator_infix_plus(struct T_Value a, struct T_Value b) {
    return T_Value_from_int(a.valueWrapper.vInt + b.valueWrapper.vInt); 
}

struct T_Value int_operator_infix_minus(struct T_Value a, struct T_Value b) {
    return T_Value_from_int(a.valueWrapper.vInt - b.valueWrapper.vInt); 
}

struct T_Value int_operator_infix_asterisk(struct T_Value a, struct T_Value b) {
    return T_Value_from_int(a.valueWrapper.vInt * b.valueWrapper.vInt); 
}

struct T_Value int_operator_infix_slash(struct T_Value a, struct T_Value b) {
    return T_Value_from_int(a.valueWrapper.vInt / b.valueWrapper.vInt); 
}

struct T_Value int_operator_infix_percent(struct T_Value a, struct T_Value b) {
    return T_Value_from_int(a.valueWrapper.vInt % b.valueWrapper.vInt); 
}

struct T_Value int_operator_infix_equals_double(struct T_Value a, struct T_Value b) {
    return T_Value_from_bool(a.valueWrapper.vInt == b.valueWrapper.vInt);
}

#endif
