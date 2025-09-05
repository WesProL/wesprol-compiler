#include <strings.h>
#include <stdbool.h>
#include <stdlib.h>
#include "string.h"

#ifndef _TYPES_NULL_H
#define _TYPES_NULL_H

struct T_Null {
    unsigned char value;
};

struct T_Null T_Null_new() {
    return (struct T_Null){0};
}

#endif
