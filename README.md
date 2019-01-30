# Simple implementation for PSR-14

This repository is based on PHP-FIG PSR-14 v0.7.

## What does it do?

This package ships with a simple dispatcher, and a listener provider which can be populated during
runtime. The `StoppableEventTrait` allows for easy usage when a task can be interrupted.

Some example events for the tests can be found in `examples/`.

Play around by calling `composer test` and have a look at the examples to build your
own PSR-14 implementation. It's fairly straightforward.

## Things I like about PSR-14

* It is based on PHP 7.2+, thus no marker interface is needed, as we deal with PHP's
simple type `object`.
* No more identification of Events based on `string` other any kind of `getName()` of
event objects.
* Do-it-yourself for the listener provider, build the one you need.


## License

MIT

## Author

Benni Mack, 2018-2019