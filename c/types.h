#ifndef _TYPES_H
#define _TYPES_H

#include "types/string.h"
#include "types/null.h"

enum Type {
    TYPE_BOOL,
    TYPE_BYTE,
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
    unsigned char vByte;
    long vInt;
    double vFloat;
    struct T_CharLiteral vChar;
    struct T_StringLiteral vString;
    // TODO: array
    // TODO: error
    // TODO: type
    // TODO: object
    struct T_Null vNull;
};

struct T_Value {
    enum Type type;
    union T_Any valueWrapper;
};

struct T_Value T_Value_from_bool(bool val) {
    return (struct T_Value){TYPE_BOOL, (union T_Any){vBool: val}};
}

struct T_Value T_Value_from_byte(unsigned char val) {
    return (struct T_Value){TYPE_BYTE, (union T_Any){vByte: val}};
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

struct T_Value T_Value_from_null() {
    return (struct T_Value){TYPE_NULL, (union T_Any){vNull: T_Null_new()}};
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
