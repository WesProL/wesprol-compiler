#include "types/string.h"

#ifndef _TYPES_H
#define _TYPES_H

enum Type {
    TYPE_BOOL,
    TYPE_INT,
    TYPE_FLOAT,
    TYPE_CHAR,
    TYPE_STRING,
    TYPE_ARRAY,
    TYPE_TYPE,
    TYPE_OBJECT,
};

union T_Any {
    bool vBool;
    long vInt;
    double vFloat;
    struct T_CharLiteral vChar;
    struct T_StringLiteral vString;
    // TODO: array
    // TODO: type
    // TODO: object
};

struct T_Value {
    enum Type type;
    union T_Any valueWrapper;
};

struct T_Value T_Value_from_bool(bool val) {
    return (struct T_Value){TYPE_BOOL, (union T_Any){vBool: val}};
}

struct T_Value T_Value_from_int(long val) {
    return (struct T_Value){TYPE_INT, (union T_Any){vInt: val}};
}

struct T_Value T_Value_from_float(double val) {
    return (struct T_Value){TYPE_FLOAT, (union T_Any){vFloat: val}};
}

struct T_Value T_Value_from_char(struct T_CharLiteral val) {
    return (struct T_Value){TYPE_CHAR, (union T_Any){vChar: val}};
}

struct T_Value T_Value_from_string(struct T_StringLiteral val) {
    return (struct T_Value){TYPE_STRING, (union T_Any){vString: val}};
}

// TODO: array
// TODO: type
// TODO: object

#endif
