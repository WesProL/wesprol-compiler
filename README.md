# WesProL

For a lack of a better name.

> The book [Writing An Interpreter In Go](https://interpreterbook.com/) got me started. Highly recommend it!

## What is WesProL?

An amalgamation of Go, Rust, C#, JS, and PHP syntax.

This ~~is~~ will be a compiler targeting C as its back-end.
The bootstrapping process will be done entirely in PHP, for personal reasons.
Once v0.0.1 is fully operable, this entire repository will be rewritten in WesProL (with some C interop).

WesProL is intended to be a compiled general purpose language.
It will feature a vast standard library, hopefully as great as Go, including HTTP server capabilities.
It also will feature interoperability with C (mostly for reliance on established C libraries to get started).

## How do I run this?

You don't. Not yet.

## Why create another pointless imperative/object-oriented language?

This will be the first proper compiler I will have ever written.

I have not spent much time with functional languages, that's why this isn't one.

It is mostly an exercise, although I intend to use this in personal projects to
prove real world application and improve the language and standard library.

## Is this a pointless waste of time?

This language does not fill any niche, it does not satisfy an active need for "something new",
it will probably die with me once my own clock runs out, but why would that stop me?

I intend to conquer the concept of writing interpreters and compilers,
first with this, next maybe with something else entirely.

## Use cases

For now and probably the near future I don't think anyone should use this.
Actually: **Please** don't use this in production code. Use:

- C, C++, Rust, Zig or Odin for your system programming
- Use Go, PHP, JS, etc. for your webservices
- Or just use any other language you are comfortable with

## The elephpant in the room

Yes, bootstrapping will be done entirely in PHP.

Why? Because that's the language I feel most comfortable writing.
I have spent (as of writing) 6 years with this language
and would consider myself quite competent with PHP.

Since this is a throwaway product, why bother writing it in Rust, OCaml or Go?
Just use the thing you know best to get the job done.
It's only for bootstrapping after all.

## I want to support and contribute to this project...

No you don't!

I know I'm likely talking to no one here.
But since it will be open source, someone might decide to help out.
Maybe some day, in a few years. Until then this is just for my personal learning.

## Examples

> The provided examples may change at any point in time.
> In fact, they already have changed several times.
> Those are just ideas for now.

### Hello World!

```php
namespace \App;

use \Standard\Format;

class Program {
    public static function main() void {
        // Format is a class, println is a static method
        Format::println("Hello World!");
    }
}
```

### Fib

```php
namespace \App;

use \Standard\Format;

class Program {
    // return fibonacci at the nth position
    private static function fib(n int) int {
        if n <= 1 {
            return n;
        }

        return fib($n - 1) + fib($n - 2);
    }

    public static function main() void {
        let fib47 int = self::fib(47);
        Std::Format::println("{}".format(fib47)); // should print "2971215073""
    }
}
```

### FizzBuzz

```php
namespace \App;

use \Standard\Format;

class Program {
    public static function main() void {
        // Rust style ranges
        for i in 1..=100 {
            Format::println(
                // if is an expression! not a statement
                if i % 15 {
                    "FizzBuzz";
                } elseif i % 3) {
                    "Fizz";
                } elseif i % 5 {
                    "Buzz";
                } else {
                    i.toString();
                },
            );
        }
    }
}
```

### All the loops

```php
namespace \App;

class Program {
    public static function main() void {
        // runs forever, until broken or returned
        loop {
        
        }

        // exclusive range 0..10, 0 to including 9
        for i in 0..10 {

        }

        // classic while
        while condition == true {

        }

        // classic do while
        do {
        
        } while condition == true;
    }
}
```

### Guess a number

```php
namespace \App;

use \Standard\Format;
use \Standard\Random;
use \Standard\Compare;
use \Standard\Ordering;

class Program {
    public static function main() void {
        let number int = Random::range(1..100);
        let guess int = 0;

        do {
            Format::print("Enter your guess: ");
            guess = Format::readLine().trim().toInt();

            // you can taste the Rust
            match Compare::compare(guess, number) {
                // Ordering is an enum
                Ordering::Less => Format::println("Your guess is too small!"), 
                Ordering::Greater => Format::println("Your guess is too large!"), 
            };
        } while guess != number;

        Format::println("You guessed correctly, it was {}!".format(number));
    }
}
```

### Typecasting

```php
namespace \App;

use \Standard\Format;

class Program {
    public static function main() void {
        let foo int = 1234;
        // no (cast) shenanigans, we just have conversion methods
        let bar float = foo.toFloat();

        Format::println(bar.toString());
    }
}
```

### Multipropsetting which I wished PHP had and kickstarted this entire project

```php
namespace \App;

use \Standard\Format;

class Program {
    public static function main() void {
        let myObject MyClass = new MyClass();
        
        // what PHP couldnt give us
        myObject.{
            foo = 13,
            bar = 37,
        };

        // this is equivalent to
        myObject.foo = 13;
        myObject.bar = 37;

        Format::println("{}{}".format(myObject.foo, myObject.bar));

        delete myObject;

        // can be easily used as initializer, returns the object instance
        let otherObject OtherClass = new OtherClass().{fooBar = 1337};
        let leet string = otherObject.toString();

        delete otherObject;    

        Format::println(leet);
    }
}
```
