#include <strings.h>
#include <stdbool.h>
#include <stdlib.h>

#ifndef _TYPES_STRING_H
#define _TYPES_STRING_H

#define T_CHAR_RAW_LENGTH 4

struct T_CharRaw {
    unsigned char bytes[T_CHAR_RAW_LENGTH];
    unsigned char length;
};

bool T_CharRaw_equal(struct T_CharRaw a, struct T_CharRaw b) {
    if (a.length != b.length) {
        return false;
    }

    for (unsigned char i = 0; i < a.length; i++) {
        if (a.bytes[0] != b.bytes[0]) {
            return false;
        }
    }

    return true;
}

struct T_CharLiteral {
    struct T_CharRaw character;
};

struct T_StringSegment {
    struct T_CharRaw character;
    struct T_StringSegment *next;
};

struct T_StringLiteral {
    struct T_StringSegment *begin;
    unsigned long length;
};

struct T_StringLiteral T_StringLiteral_new(struct T_CharRaw *characters, unsigned long length) {
    struct T_StringSegment *first;
    void *current = (void *)0;
    for (unsigned long i = 0; i < length; i++) {
        struct T_StringSegment *next = (struct T_StringSegment *)malloc(sizeof(struct T_StringSegment));
        next->character.length = characters[i].length;
        for (unsigned char j = 0; j < characters[i].length; j++) {
            next->character.bytes[j] = characters[i].bytes[j];
        }

        if (current == 0) {
            first = next;
        } else {
            ((struct T_StringSegment *)current)->next = next; 
        }

        current = next;
    }

    struct T_StringLiteral string;
    string.begin = first;
    string.length = length;

    return string;
}

void T_StringLiteral_delete(struct T_StringLiteral literal) {
    struct T_StringSegment *next = literal.begin;
    for (unsigned long i = 1; i < literal.length; i++) {
        struct T_StringSegment *n = next->next;
        free(next);
        next = n;
    }

    free(next);
}

struct T_StringLiteral T_StringLiteral_copy(struct T_StringLiteral src) {
    struct T_StringSegment *first;
    void *current = (void *)0;
    struct T_StringSegment *srcCurrent = src.begin;
    for (unsigned long i = 0; i < src.length; i++) {
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
    string.length = src.length;

    return string;
}

char *T_StringLiteral_toCString(struct T_StringLiteral literal) {
    char *string = (char *)malloc(literal.length * T_CHAR_RAW_LENGTH);
    char *s = string;

    struct T_StringSegment *current = literal.begin;
    for (unsigned long i = 0; i < literal.length; i++) {
        for (char j = 0; j < current->character.length; j++) {
            *s = current->character.bytes[j];
            s++;
        }

        current = current->next;
    }
    *s = 0;

    return string;
}

#endif
