# RSSNotifier

Sends a telegram message every time a new item is found in a RSS feed file. Also posts it to Facebook, Twitter and sends a notification to an Android app.

This code uses:

* **pqrs/checkrss**: My repo to check RSS feed for new items.
* **unreal4u/telegram-api**: Telegram bot API implementation for PHP
* **facebook/php-graph-sdk**: The Facebook SDK for PHP
* **abraham/twitteroauth**: The most popular PHP library for use with the Twitter OAuth REST API.
* [**monolog/monolog**](https://github.com/Seldaek/monolog): Sends your logs to files, sockets, inboxes, databases and various web services


## Installation

```
git clone https://github.com/pqrs/rssnotifier.git
```

Alternatively, add the dependency directly to your composer.json file:

```
"require": {
    "pqrs/checkrss": "dev-master",
    "unreal4u/telegram-api": "~2.3",
    "facebook/graph-sdk" : "5.x",
    "abraham/twitteroauth": "^0.7.4",
    "monolog/monolog": "^1.23"
}
```

Then add to your php code:

``` php
require_once __DIR__ . '/vendor/autoload.php';   // Autoload files using Composer autoload

use CheckRSS\RSS;
```


## Examples

You will find some uses for these functions in [tests folder](tests). **Come back in a few days**


## Prerequisites

PHP 7


## Contributing

Contributions are of course very welcome!


## Credits

**Copyright © 2018 Alvaro Piqueras** - [pqrs](https://github.com/pqrs)


## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

