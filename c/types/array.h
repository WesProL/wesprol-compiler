#include <strings.h>
#include <stdbool.h>
#include <stdlib.h>
#include "string.h"
#include "../types.h"
#include "../valuehelper.h"

#ifndef _TYPES_ARRAY_H
#define _TYPES_ARRAY_H

bool T_Value_equal(struct T_Value a, struct T_Value b);
struct T_Value T_Value_clone(struct T_Value val);

// Jenkins one_at_a_time
unsigned int generate_T_Array_hash(struct T_StringLiteral input) {
    char *key = T_StringLiteral_toCString(input);

    unsigned long i = 0;
    unsigned int hash = 0;
    while (i != input.length) {
        hash += key[i++];
        hash += hash << 10;
        hash ^= hash >> 6;
    }
    hash += hash << 3;
    hash ^= hash >> 11;
    hash += hash << 15;

    free(key);

    return hash;
}

struct T_ArrayBucketEntry {
    struct T_Value key;
    struct T_Value value;
    struct T_ArrayBucketEntry *next;
};

struct T_ArrayBucketEntry T_ArrayBucketEntry_new(struct T_Value key, struct T_Value value) {
    return (struct T_ArrayBucketEntry){key, value, 0};
};

struct T_ArrayInternal {
    unsigned long length;
    unsigned long capacity;
    unsigned long nextIntKey;
    struct T_ArrayBucketEntry *entries;
};

struct T_ArrayInternal T_ArrayInternal_new(unsigned long capacity) {
    struct T_ArrayInternal result = {0, capacity, 0, 0};
    result.entries = (struct T_ArrayBucketEntry *)malloc(sizeof(struct T_ArrayBucketEntry) * capacity);

    for (unsigned long i = 0; i < capacity; i++) {
        result.entries[i] = T_ArrayBucketEntry_new(T_Value_from_null(), T_Value_from_null());
    }

    return result;
}

struct T_Array {
    struct T_ArrayInternal internal;
};

struct T_Array T_Array_new(unsigned long capacity) {
    return (struct T_Array){T_ArrayInternal_new(capacity)};    
}

void T_Array_delete(struct T_Array arr) {
    // TODO: delete from heap
}

struct T_Value T_Array_find(struct T_Array arr, struct T_Value key) {
    struct T_ArrayInternal a = arr.internal;

    if (a.entries == 0) {
        return T_Value_from_null();
    }

    unsigned int k;
    switch (key.type) {
        case TYPE_STRING:
            k = generate_T_Array_hash(key.valueWrapper.vString);
            break;
        case TYPE_BOOL:
            k = (unsigned int)key.valueWrapper.vBool;
            break;
        case TYPE_INT:
            k = (unsigned int)key.valueWrapper.vInt;
            break;
        case TYPE_FLOAT:
            k = (unsigned int)key.valueWrapper.vFloat;
            break;
        default:
            return T_Value_from_null();
    }

    k %= a.capacity;
    struct T_ArrayBucketEntry *current = a.entries + k;
    while (true) {
        if (current->key.type != TYPE_NULL && T_Value_equal(current->key, key)) {
            return T_Value_clone(current->value);
        }

        if (current->next == 0) {
            return T_Value_from_null();
        }

        current = current->next;
    }

}

void T_Array_set(struct T_Array arr, struct T_Value key, struct T_Value value) {
    struct T_ArrayInternal a = arr.internal;

    unsigned int k;
    switch (key.type) {
        case TYPE_STRING:
            k = generate_T_Array_hash(key.valueWrapper.vString);
            break;
        case TYPE_BOOL:
            k = (unsigned int)key.valueWrapper.vBool;
            break;
        case TYPE_INT:
            // TODO: IF INT TYPE, MAKE SURE TO INCREASE nextIntKey
            k = (unsigned int)key.valueWrapper.vInt;
            break;
        case TYPE_FLOAT:
            k = (unsigned int)key.valueWrapper.vFloat;
            break;
        default:
            return;
    }

    k %= a.capacity;
    struct T_ArrayBucketEntry *current = a.entries + k;
    while (true) {
        if (current->key.type != TYPE_NULL && T_Value_equal(current->key, key)) {
            current->value = value;
        }

        if (current->next == 0) {
            struct T_ArrayBucketEntry *next = (struct T_ArrayBucketEntry *)malloc(sizeof(struct T_ArrayBucketEntry));
            *next = T_ArrayBucketEntry_new(key, value);
            current->next = next;

            return;
        }

        current = current->next;
    }
}

// TODO: array push

#endif
