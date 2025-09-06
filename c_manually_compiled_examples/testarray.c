#include <stdio.h>
#include "../c/include.h"

void assertNull(struct T_Value value) {
    if (value.type != TYPE_NULL) {
        printf("NOT NULL!\n");
    }
}

int main() {
    struct T_Array arr = T_Array_new(10); 

    struct T_CharRaw a_cr[] = {
        {{'F', 0, 0, 0}, 1},
        {{'o', 0, 0, 0}, 1},
        {{'o', 0, 0, 0}, 1},
    };
    struct T_StringLiteral a = T_StringLiteral_new(a_cr, 3);
    
    struct T_CharRaw b_cr[] = {
        {{'B', 0, 0, 0}, 1},
        {{'a', 0, 0, 0}, 1},
        {{'r', 0, 0, 0}, 1},
    };
    struct T_StringLiteral b = T_StringLiteral_new(b_cr, 3);

    T_Array_set(arr, T_Value_from_string(a), T_Value_from_float(13.0));
    T_Array_set(arr, T_Value_from_int(1234), T_Value_from_float(0.37));

    struct T_Value res1 = T_Array_get(arr, T_Value_from_string(a));
    struct T_Value res2 = T_Array_get(arr, T_Value_from_string(b));
    struct T_Value res3 = T_Array_get(arr, T_Value_from_int(1337));
    struct T_Value res4 = T_Array_get(arr, T_Value_from_int(1234));

    assertNull(res2);
    assertNull(res3);

    printf("%f\n", res1.valueWrapper.vFloat + res4.valueWrapper.vFloat);

    return 0;
}
