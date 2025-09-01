#include <stdio.h>
#include "c/strings.c"

/*
public static function main() void {
    let foo = "Hello 😀";
    Format::println(foo);
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
    struct T_StringLiteral _string_literal_87243723 = _T_StringLiteral_new(_string_literal_87243723_characters, _string_literal_87243723_length);
    struct T_StringLiteral _foo_84630692 = _T_StringLiteral_copy(_string_literal_87243723);

    // [DEMO]
    // Format::println() would in reality look quite different, this is just a quick example
    char *printMe = _T_StringLiteral_toCString(_foo_84630692);
    printf("%s\n", printMe);
    free(printMe);
    // [/DEMO]

    // foo technically leaks memory here, but the literal that foo uses is cleaned up automatically
    // foo could be freed manually, but i deliberately didnt show it here

    _T_StringLiteral_delete(_string_literal_87243723);

    return 0;
}