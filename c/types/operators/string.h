#include "../../types.h"
#include "../string.h"
#include "char.h"

#ifndef _TYPES_OPERATORS_STRING_H
#define _TYPES_OPERATORS_STRING_H

struct T_Value string_operator_infix_plus(struct T_Value a, struct T_Value b) {
    struct T_StringLiteral src1 = a.valueWrapper.vString;
    struct T_StringLiteral src2 = b.valueWrapper.vString;
    
    long length = src1.length + src2.length;

    struct T_StringSegment *first;
    void *current = (void *)0;
    struct T_StringSegment *srcCurrent = src1.begin;
    for (long i = 0; i < src1.length; i++) {
        struct T_StringSegment *next = (struct T_StringSegment *)malloc(sizeof(struct T_StringSegment));
        next->character.length = srcCurrent->character.length;

        for (char j = 0; j < srcCurrent->character.length; j++) {
            next->character.bytes[j] = srcCurrent->character.bytes[j];
        }

        if (current == 0) {
            first = next;
        } else {
            ((struct T_StringSegment *)current)->next = next;  
        }

        current = next;
        srcCurrent = ((struct T_StringSegment *)srcCurrent)->next;
    }

    srcCurrent = src2.begin;
    for (long i = 0; i < src2.length; i++) {
        struct T_StringSegment *next = (struct T_StringSegment *)malloc(sizeof(struct T_StringSegment));
        next->character.length = srcCurrent->character.length;

        for (char j = 0; j < srcCurrent->character.length; j++) {
            next->character.bytes[j] = srcCurrent->character.bytes[j];
        }

        if (current == 0) {
            first = next;
        } else {
            ((struct T_StringSegment *)current)->next = next;  
        }

        current = next;
        srcCurrent = ((struct T_StringSegment *)srcCurrent)->next;
    }

    struct T_StringLiteral string;
    string.begin = first;
    string.length = length;

    return T_Value_from_string(string); 
}

struct T_Value string_operator_infix_equals_double(struct T_Value a, struct T_Value b) {
    if (a.valueWrapper.vString.length != b.valueWrapper.vString.length) {
        return T_Value_from_bool(false);
    }

    struct T_StringSegment *aNext = a.valueWrapper.vString.begin; 
    struct T_StringSegment *bNext = b.valueWrapper.vString.begin; 
    for (long i = 1; i < a.valueWrapper.vString.length; i++) {
        if (!T_CharRaw_equal(aNext->character, bNext->character)) {
            return T_Value_from_bool(false);
        }

        aNext = aNext->next;
        bNext = bNext->next;
    }

    return T_Value_from_bool(true);
}

#endif
