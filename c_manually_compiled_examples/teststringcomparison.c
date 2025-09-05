#include <stdio.h>
#include "../c/include.h"

/*
public static function main() void {
    let val1 string = "Aa!?";
    let val2 string = "Aa!?";
    let val3 string = "AaB?";

    if (val1 == val2) {
        Format::println("1");
    }

    if (val2 == val3) {
        Format::println("2");
    }

    if (val2 == val1) {
        Format::println("3");
    }
}
*/

int main() {
    long _string_literal_93752018_length = 4;
    struct T_CharRaw _string_literal_93752018_characters[] = {
        {{'A', 0, 0, 0}, 1},
        {{'a', 0, 0, 0}, 1},
        {{'!', 0, 0, 0}, 1},
        {{'?', 0, 0, 0}, 1},
    };
    struct T_StringLiteral _string_literal_93752018 = T_StringLiteral_new(_string_literal_93752018_characters, _string_literal_93752018_length);
    struct T_Value _val1_74392910 = T_Value_from_string(T_StringLiteral_copy(_string_literal_93752018));
    T_StringLiteral_delete(_string_literal_93752018);

    long _string_literal_38362031_length = 4;
    struct T_CharRaw _string_literal_38362031_characters[] = {
        {{'A', 0, 0, 0}, 1},
        {{'a', 0, 0, 0}, 1},
        {{'!', 0, 0, 0}, 1},
        {{'?', 0, 0, 0}, 1},
    };
    struct T_StringLiteral _string_literal_38362031 = T_StringLiteral_new(_string_literal_38362031_characters, _string_literal_38362031_length);
    struct T_Value _val2_58973241 = T_Value_from_string(T_StringLiteral_copy(_string_literal_38362031));
    T_StringLiteral_delete(_string_literal_38362031);

    long _string_literal_02539112_length = 4;
    struct T_CharRaw _string_literal_02539112_characters[] = {
        {{'A', 0, 0, 0}, 1},
        {{'a', 0, 0, 0}, 1},
        {{'B', 0, 0, 0}, 1},
        {{'?', 0, 0, 0}, 1},
    };
    struct T_StringLiteral _string_literal_02539112 = T_StringLiteral_new(_string_literal_02539112_characters, _string_literal_02539112_length);
    struct T_Value _val3_12993742 = T_Value_from_string(T_StringLiteral_copy(_string_literal_02539112));
    T_StringLiteral_delete(_string_literal_02539112);

    if (string_operator_infix_equals_double(_val1_74392910, _val2_58973241).valueWrapper.vBool) {
        printf("1\n");
    }
    
    if (string_operator_infix_equals_double(_val2_58973241, _val3_12993742).valueWrapper.vBool) {
        printf("2\n");
    }

    if (string_operator_infix_equals_double(_val2_58973241, _val1_74392910).valueWrapper.vBool) {
        printf("3\n");
    }

    return 0;
}
