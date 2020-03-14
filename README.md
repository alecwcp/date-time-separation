# Date Time Separation

#### Background

Handling date-times in PHP can be done with "relative" ease through usage of the in-built `date` and `date_*`
functions, the `DateTime` and `DateTime` classes, or even with useful libraries like [Carbon](https://carbon.nesbot.com/docs/).

Dealing with dates and times as separate entities remains awkward. A common solution is to use the date-time
objects still and just ignore either the date or time part respectively - however, this then requires better variable
naming, additional comments, doesn't give any extra type safety and makes comparisons harder.

This library aims to make dealing with dates and times as separate entities simple by providing 2 slim interfaces which
are very similar to the core `DateTimeInterface` and 2 simple classes which implement these interfaces and provide
a few convenience methods based on `DateTimeImmutable`.

#### Internals

The internals of the library lean heavily on `DateTimeImmutable` to handle formatting etc.

#### Extending

Extenders can either implement the interfaces provided here, or extend the simple classes provided here (within the
classes all references are to `static` rather than `self` to allow easily overriding their behaviour).

#### Contributing

This library adheres to the PSR12 coding standard. Run the style check by running
```
./vendor/bin/phpcs --standard="phpcs.xml" src
```

This library uses [Psalm](https://psalm.dev/docs/) for static analysis. Run the analyzer by running
```
./vendor/bin/psalm
```

This library uses [PHPUnit](https://phpunit.readthedocs.io/en/8.0/) for testing. Run the tests by running
```
./vendor/bin/phpunit tests
```
