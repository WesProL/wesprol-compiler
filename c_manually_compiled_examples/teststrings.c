#include <stdio.h>
#include "../c/include.h"

/*
public static function main() void {
    let foo string = "Hello 😀";
    let bar string = foo + " World!";
    Format::println(bar);
}
*/

int main() {
    long _string_literal_87243723_length = 7;
    struct T_CharRaw _string_literal_87243723_characters[] = {
        {{'H', 0, 0, 0}, 1},
        {{'e', 0, 0, 0}, 1},
        {{'l', 0, 0, 0}, 1},
        {{'l', 0, 0, 0}, 1},
        {{'o', 0, 0, 0}, 1},
        {{' ', 0, 0, 0}, 1},
        {{0xF0, 0x9F, 0x98, 0x80}, 4},
    };
    struct T_StringLiteral _string_literal_87243723 = T_StringLiteral_new(_string_literal_87243723_characters, _string_literal_87243723_length);
    struct T_Value _foo_84630692 = T_Value_from_string(T_StringLiteral_copy(_string_literal_87243723));
    T_StringLiteral_delete(_string_literal_87243723);

    long _string_literal_72848123_length = 7;
    struct T_CharRaw _string_literal_72848123_characters[] = {
        {{' ', 0, 0, 0}, 1},
        {{'W', 0, 0, 0}, 1},
        {{'o', 0, 0, 0}, 1},
        {{'r', 0, 0, 0}, 1},
        {{'l', 0, 0, 0}, 1},
        {{'d', 0, 0, 0}, 1},
        {{'!', 0, 0, 0}, 1},
    };
    struct T_StringLiteral _string_literal_72848123 = T_StringLiteral_new(_string_literal_72848123_characters, _string_literal_72848123_length);
    struct T_Value _bar_2038273 = string_operator_infix_plus(_foo_84630692, T_Value_from_string(_string_literal_72848123));
    T_StringLiteral_delete(_string_literal_72848123);

    // [DEMO]
    // Format::println() would in reality look quite different, this is just a quick example
    char *printMe = T_StringLiteral_toCString(_bar_2038273.valueWrapper.vString);
    printf("%s\n", printMe);
    free(printMe);
    // [/DEMO]

    // foo and bar technically leak memory here, but the literal that foo/bar uses is cleaned up automatically
    // either could be freed manually, but i deliberately didnt show it here

    return 0;
}
