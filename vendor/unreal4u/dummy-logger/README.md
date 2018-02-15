# unreal4u/dummy-logger

Lightweight PSR-3 compatible Logging Framework for PHP7+ which, as the name implies, does not log or do anything else.

This class only exists because I often will create my packages with a lot of debugging and other statements, and I see
myself on the necessity of having this class defined in the package itself, which breaks the rule that a package should
do one and only one thing correctly.

If you are facing the same problem, you can also use this class. It is released under the MIT license.

This class will also inherit some Monolog exclusive methods as I often see myself using these ones. New methods may be
added in the future.

# What is ...?

## PSR-3

PSR-3 came into life as a way to create a common interface for Logger Frameworks: this way, you can rewrite libraries to
receive a `Psr\Log\LoggerInterface` object and write logs to it in a simple and universal way.

## Monolog

Monolog is the de-facto standard framework used by the PHP community in order to log stuff. You can check the Monolog's
[homepage here](https://github.com/Seldaek/monolog).

# Clarifications

No tests needed because this package does not do any processing of data. In the event that it will in the future, tests
will be added.
