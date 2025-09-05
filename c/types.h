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
    TYPE_ERROR,
    TYPE_TYPE,
    TYPE_OBJECT,
    TYPE_NULL,
};

union T_Any {
    bool vBool;
    long vInt;
    double vFloat;
    struct T_CharLiteral vChar;
    struct T_StringLiteral vString;
    // TODO: array
    // TODO: error
    // TODO: type
    // TODO: object
    // TODO: null
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
// TODO: error
// TODO: type
// TODO: object
// TODO: null

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
            return T_Value_from_string(val.valueWrapper.vString);
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
            // TODO
            break;
    }
}

union T_Reference {
    union T_Reference *ref;
    struct T_Value *val;
};

union T_Reference T_Reference_of_T_Value(struct T_Value *val) {
    return (union T_Reference){val: val};
}

union T_Reference T_Reference_of_T_Reference(union T_Reference *ref) {
    return (union T_Reference){ref:ref};
}

#endif
