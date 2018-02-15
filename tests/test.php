<?php

define( "TIMEZONE", "Europe/Madrid"                                         ); // Your timezone
define( "RSS_FEED", "https://www.cnbc.com/id/100727362/device/rss/rss.html" ); // The RSS feed URL

// Telegram
define( "TELEGRAM_USER_ID", "XXXXXXXX"                                      ); // Your telegram User ID
define( "BOT_AUTH_TOKEN",   "XXXXXXXXX:XXXXXXXXXXXXXXXXXXXXXXXXXXX-XXXXXXX" ); // Telegram bot token

// Facebook
define( 'APP_ID',            "XXXXXXXXXXXXXXX" );
define( 'APP_SECRET',        "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" );
define( 'GROUP_ID',          "XXXXXXXXXXXXXXX" );
define( 'PAGE_ACCESS_TOKEN', "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" );

// Twitter
define( "CONSUMER_KEY",      "XXXXXXXXXXXXXXXXXXXXXXXXX" );
define( "CONSUMER_SECRET",   "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" );
define( "OAUTH_TOKEN",       "XXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" );
define( "OAUTH_SECRET",      "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" );

// Android App
define( "API_ACCESS_KEY", "XXXXXXXXXXX:XXXXXXXXXXXXXXXXXXXXXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" );


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


        if (post2twitter( CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET, $value->title . " " . $value->link ) ) {

            post2telegram(TELEGRAM_USER_ID, BOT_AUTH_TOKEN, "PUBLICADO EN TWITTER: " . $value->title . PHP_EOL . PHP_EOL . strip_tags($value->description) . PHP_EOL . PHP_EOL . $value->link );
            logit( "Twittter", "New tweet -> $value->title", "info" );


        } else {

            post2telegram(TELEGRAM_USER_ID, BOT_AUTH_TOKEN, "ERROR: No se pudo publicar en Twitter la noticia '" . $value->title . "'" );
            logit( "Twitter", "Couldn't publish to Twitter -> $value->title", "error" );

        }


        if (androidnotification( API_ACCESS_KEY, $value->title ) ) {

            post2telegram(TELEGRAM_USER_ID, BOT_AUTH_TOKEN, "ENVIADA NOTIFICACIÓN ANDROID: " . $value->title );
            logit( "Android", "New Android notification -> $value->title", "info" );

        } else { 

            post2telegram(TELEGRAM_USER_ID, BOT_AUTH_TOKEN, "ERROR: No se pudo enviar a dispositivos Android '" . $value->title . "'" );
            logit( "Android", "Couldn't notify to Android app -> $value->title", "error" );

        }

    }

} else {

    logit( "RSS", "No new items found", "info" );

}

?>