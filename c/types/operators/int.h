#include "../../types.h"

#ifndef _TYPES_OPERATORS_INT_H
#define _TYPES_OPERATORS_INT_H

struct T_Value int_plus(struct T_Value a, struct T_Value b) {
    return T_Value_from_int(a.valueWrapper.vInt + b.valueWrapper.vInt); 
}

#endif
