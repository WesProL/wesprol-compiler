#include <stdio.h>
#include "../c/include.h"

/*
public static function main() void {
    let foo int = 100;
    let bar int = foo;
    let fooref &int = &foo;

    bar += 1;
    *fooref += 2;

    Format::print("Foo: {}\nBar: {}\nFooRef: {}\n".format(foo, bar, *fooref));
}
*/

int main() {
    struct T_Value _foo_9236502 = T_Value_from_int(100);
    struct T_Value _bar_8923264 = T_Value_clone(_foo_9236502);
    union T_Reference _fooref_7684359 = T_Reference_of_T_Value(&_foo_9236502);

    _bar_8923264 = int_operator_infix_plus(_bar_8923264, T_Value_from_int(1));
    (*(_fooref_7684359.val)) = int_operator_infix_plus((*(_fooref_7684359.val)), T_Value_from_int(2));

    // we just ignore what .format() would do here and use printf for testing
    printf(
        "Foo: %ld\nBar: %ld\nFooRef: %ld\n",
        _foo_9236502.valueWrapper.vInt,
        _bar_8923264.valueWrapper.vInt,
        (*(_fooref_7684359.val)).valueWrapper.vInt
    );

    return 0;
}
