# RSSNotifier

Sends a Telegram message every time a new item is found in an RSS feed file. Also posts it to Facebook, Twitter and sends a notification to an Android app and keeps a log of every event.

I use it to connect the content published in a sports club web page with its social networking accounts. Every time one of these events happens I receive a Telegram message. The program is ran through a cron job and produces no screen output but keeps a log of every event.

This code uses:

* [**unreal4u/telegram-api**](https://github.com/unreal4u/telegram-api): Telegram bot API implementation for PHP
* [**facebook/php-graph-sdk**](https://github.com/facebook/php-graph-sdk): The Facebook SDK for PHP
* [**abraham/twitteroauth**](https://github.com/abraham/twitteroauth): The most popular PHP library for use with the Twitter OAuth REST API.
* [**Seldaek/monolog**](https://github.com/Seldaek/monolog): Sends your logs to files, sockets, inboxes, databases and various web services.
* [**pqrs/checkrss**](https://github.com/pqrs/checkrss): My repo to check RSS feed for new items.


## Installation

``` 
git clone https://github.com/pqrs/rssnotifier.git
```

Alternatively, add the dependencies directly to your composer.json file:

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

You will find some uses for these functions in [tests folder](tests). **I'm currently writing it, please come back in a few days**


## Prerequisites

PHP 7


## Contributing

Contributions are of course very welcome!


## Credits

* [**unreal4u/telegram-api**](https://github.com/unreal4u/telegram-api): Copyright (c) 2016 Camilo Sperberg
* [**facebook/php-graph-sdk**](https://github.com/facebook/php-graph-sdk): Copyright © 2017 Facebook, Inc.
* [**abraham/twitteroauth**](https://github.com/abraham/twitteroauth): Copyright (c) 2009 Abraham Williams - http://abrah.am - abraham@abrah.am
* [**Seldaek/monolog**](https://github.com/Seldaek/monolog): Copyright (c) 2011-2017 Jordi Boggiano - j.boggiano@seld.be - http://twitter.com/seldaek
* [**pqrs/checkrss**](https://github.com/pqrs/checkrss): Copyright © 2018 Alvaro Piqueras - [pqrs](https://github.com/pqrs)






## License

This project and all the repositories used are licensed under the [MIT License](LICENSE) except for [**facebook/php-graph-sdk**](https://github.com/facebook/php-graph-sdk).

Please see the Facebook SDK for PHP (v5) [license file](https://github.com/facebook/php-graph-sdk/blob/master/LICENSE) for more information.

