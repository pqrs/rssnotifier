# RSSNotifier

Sends a Telegram message every time a new item is found in an RSS feed file. Also posts it to Facebook, Twitter and sends a notification to an Android app and keeps a log of every event.

I use it to connect the content published in a web site with its social networking accounts. Every time one of these events happens I receive a Telegram message. The program is ran through a cron job and produces no screen output but keeps a log of every event.

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


## Usage

Create a file *rssnotifier.php* in the root diretory and let's start with the RSS feed URL you want to check:

``` php
define( "TIMEZONE", "Europe/Madrid"                                         ); // Your timezone
define( "RSS_FEED", "https://www.cnbc.com/id/100727362/device/rss/rss.html" ); // The RSS feed URL
```

Now, put your Telegram User Id and the Auth Token for your Telegram bot:

``` php
define( "TELEGRAM_USER_ID", "XXXXXXXX"                                      ); // Your telegram User ID
define( "BOT_AUTH_TOKEN",   "XXXXXXXXX:XXXXXXXXXXXXXXXXXXXXXXXXXXX-XXXXXXX" ); // Telegram bot token
```

That's enough for now. Now we are going to check the RSS feed for new items. If there are new ones, we are going to send it to our Telegram through our bot:

``` php
date_default_timezone_set(TIMEZONE);

require_once __DIR__ . '/vendor/autoload.php';                   // Autoload files using Composer autoload

include __DIR__ . '/includes/functions.php';

use CheckRSS\RSS;

$rss = new RSS;

// Gets all the items published in the rss feed and stores them in $items
$items = $rss->getItems(RSS_FEED);

// Checks which items are new since last check
if ($newitems = $rss->getNewItems($items) ) {

    foreach ($newitems as $value) {

        post2telegram(TELEGRAM_USER_ID, BOT_AUTH_TOKEN, "NEW ITEM: " . $value->title);     // Sends item title to telegram

        logit( "RSS", "New item found -> $value->title", "info" );

    }

} else {

	logit( "RSS", "No new items found", "info" );

}


```

Whether there are new items in the feed or not, we write a message to our log with the *logit()* function. You'll find the log in your root directory too, named *rssnotifier.log*.

If we want to post the new found item to our facebook page we must add this definitions under the Telegram defines above:

``` php
define( 'APP_ID',            "XXXXXXXXXXXXXXX" );
define( 'APP_SECRET',        "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" );
define( 'GROUP_ID',          "XXXXXXXXXXXXXXX" );
define( 'PAGE_ACCESS_TOKEN', "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" );
```

And now, inside the foreach loop, under the *logit( "RSS", "New item found [...]* line we wrote before:

``` php
        $linkData = [                                                       // Sends item link & description to FB page
            'link'      => $value->link,
            'message'   => strip_tags($value->description),
        ];

        if (post2facebookpage(APP_ID, APP_SECRET, GROUP_ID, PAGE_ACCESS_TOKEN, $linkData)) {

            post2telegram(TELEGRAM_USER_ID, BOT_AUTH_TOKEN, "PUBLICADO EN FACEBOOK: " . $value->title . PHP_EOL . PHP_EOL . strip_tags($value->description) . PHP_EOL . PHP_EOL . $value->link );
            logit( "Facebook", "New publication in Facebook -> $value->title", "info" );           

        } else {

            post2telegram(TELEGRAM_USER_ID, BOT_AUTH_TOKEN, "ERROR: No se pudo publicar en Facebook la noticia '" . $value->title . "'" );
            logit( "Facebook", "Couldn't publish to Facebook -> $value->title", "error" );

        }
```

If you want to publish in your personal page instead of a fan page, you shoud use the function *post2facebookpersonalwall()*. You'll only need the parameters APP_ID, APP_SECRET and of course $linkdata.

## Prerequisites

* PHP 7
* An RSS feed to start it all

And, at least, one of these:

* A Facebook personal or fan page
* A Twitter account
* A Telegram account and a Telegram bot
* An Android application


## Contributing

Contributions are of course very welcome!


## Credits

* [**unreal4u/telegram-api**](https://github.com/unreal4u/telegram-api): Copyright © 2016 Camilo Sperberg
* [**facebook/php-graph-sdk**](https://github.com/facebook/php-graph-sdk): Copyright © 2017 Facebook, Inc.
* [**abraham/twitteroauth**](https://github.com/abraham/twitteroauth): Copyright © 2009 Abraham Williams - http://abrah.am - abraham@abrah.am
* [**Seldaek/monolog**](https://github.com/Seldaek/monolog): Copyright © 2011-2017 Jordi Boggiano - j.boggiano@seld.be - http://twitter.com/seldaek
* [**pqrs/checkrss**](https://github.com/pqrs/checkrss): Copyright © 2018 Alvaro Piqueras - [pqrs](https://github.com/pqrs)


## License

This project and all the repositories used are licensed under the [MIT License](LICENSE) except for [**facebook/php-graph-sdk**](https://github.com/facebook/php-graph-sdk).

Please see the Facebook SDK for PHP (v5) [license file](https://github.com/facebook/php-graph-sdk/blob/master/LICENSE) for more information.

