#include <strings.h>
#include <stdbool.h>
#include <stdlib.h>

#define T_CHARRAW_LENGTH 4

struct T_CharRaw {
    char bytes[T_CHARRAW_LENGTH];
    char length;
};

struct T_CharLiteral {
    struct T_CharRaw character;
};

struct T_StringSegment {
    struct T_CharRaw character;
    struct T_StringSegment *next;
};

struct T_StringLiteral {
    struct T_StringSegment *begin;
    long length;
};

struct T_StringLiteral _T_StringLiteral_new(struct T_CharRaw *characters, long length) {
    struct T_StringSegment *first;
    void *current = (void *)0;
    for (long i = 0; i < length; i++) {
        struct T_StringSegment *next = (struct T_StringSegment *)malloc(sizeof(struct T_StringSegment));
        next->character.length = characters[i].length;
        for (char j = 0; j < characters[i].length; j++) {
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

void _T_StringLiteral_delete(struct T_StringLiteral literal) {
    struct T_StringSegment *next = literal.begin;
    for (long i = 1; i < literal.length; i++) {
        struct T_StringSegment *n = next->next;
        free(next);
        next = n;
    }

    free(next);
}

struct T_StringLiteral _T_StringLiteral_copy(struct T_StringLiteral src) {
    struct T_StringSegment *first;
    void *current = (void *)0;
    struct T_StringSegment *srcCurrent = src.begin;
    for (long i = 0; i < src.length; i++) {
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

char *_T_StringLiteral_toCString(struct T_StringLiteral literal) {
    char *string = malloc(literal.length * T_CHARRAW_LENGTH);
    char *s = string;

    struct T_StringSegment *current = literal.begin;
    for (long i = 0; i < literal.length; i++) {
        for (char j = 0; j < current->character.length; j++) {
            *s = current->character.bytes[j];
            s++;
        }

        current = current->next;
    }
    *s = 0;

    return string;
}
