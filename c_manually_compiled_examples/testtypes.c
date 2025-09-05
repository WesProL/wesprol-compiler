#include <stdio.h>
#include "../c/include.h"

/*
public static function main() void {
    let foo int = 1300;
    let bar int = 37;

    Format::println("{}".format(foo + bar));
}
*/

int main() {
    struct T_Value _foo_6740810 = T_Value_from_int(1300);
    struct T_Value _bar_1673048 = T_Value_from_int(37);

    // we just ignore what .format() would do here and use printf for testing
    printf("%ld\n", int_operator_infix_plus(_foo_6740810, _bar_1673048).valueWrapper.vInt);

    return 0;
}