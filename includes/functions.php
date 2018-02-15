<?php

use GuzzleHttp\Exception\ClientException;
use \unreal4u\TelegramAPI\TgLog;
use \unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use Abraham\TwitterOAuth\TwitterOAuth;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

    
function post2telegram($user_id, $bot_token, $message) {

    $tgLog = new TgLog($bot_token);

    $sendMessage = new SendMessage();

    $sendMessage->chat_id = $user_id;

    $sendMessage->text = $message;

    try {

        $tgLog->performApiRequest($sendMessage);

    } catch (ClientException $e) {

        echo 'Error detected trying to send message to user: <pre>';
        var_dump($e->getRequest());
        echo '</pre>';
        die();

    }

};



function post2facebookpage( $app_id, $app_secret, $group_id, $page_aceess_token, $linkData ) {

    $fb = new Facebook\Facebook(array(
      'app_id'                  => $app_id,
      'app_secret'              => $app_secret,
      'default_graph_version'   => 'v2.4'
    ));


    try {

        $fb->post('/' . $group_id . '/feed', $linkData, $page_aceess_token);

    } catch (Facebook\Exceptions\FacebookResponseException $e) {

        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        return false;

    } catch (Facebook\Exceptions\FacebookSDKException $e) {

        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        return false;

    }

    return true;

}



function post2facebookpersonalwall( $app_id, $app_secret, $linkData ) {

    $fb = new Facebook\Facebook(array(
      'app_id'                  => $app_id,
      'app_secret'              => $app_secret,
      'default_graph_version'   => 'v2.4'
    ));

    $helper = $fb->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken();

        $fb->post('/feed', $linkData, $accessToken);

    } catch (Facebook\Exceptions\FacebookResponseException $e) {

        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        return false;

    } catch (Facebook\Exceptions\FacebookSDKException $e) {

        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        return false;

    }

    return true;

}


function post2twitter( $consumer_key, $consumer_secret, $oauth_token, $oauth_secret, $message ) {

    $connection = new TwitterOAuth( $consumer_key, $consumer_secret, $oauth_token, $oauth_secret );
    
    $content = $connection->get('account/verify_credentials');

    $connection->post('statuses/update', array('status' => $message));

    if ($connection->getLastHttpCode() == 200) {
        
        return true;

    } else {
        
        return false;

    }

}


function androidnotification( $api_access_key, $message ) {

    // prep the bundle
    $msg = array (
        'body'          => $texto,
        'title'         => 'Club de Hockey San Fernando',
        'priority'      => 'high',
        'sound'         => 'default',
        'time_to_live'  => 3600
        );

    $fields = array(
        'to'            => '/topics/general',
        'notification'  => $msg
        );

    $headers = array(
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);

    curl_close($ch);

    //Decoding json from result 
    $res = json_decode($result);

    $flag = $res->success;

    //if success is 1 means message is sent 
    if($flag == 1){
        
        return true;

    } else {

        return false;
    }


    $message_id = substr($result, strpos($result, ":")+1, -1);

    $json = json_decode(file_get_contents("/var/www/vhosts/chsanfernando.org/app.chsanfernando.org/data.txt"), true);

    $data = array('id' => $message_id, 'date' =>  date( "d/m/Y" ), 'time' => date( "H:i" ), 'message' => $texto);

    array_push($json['notifications'], $data);

    file_put_contents('/var/www/vhosts/chsanfernando.org/app.chsanfernando.org/data.txt', json_encode($json));

    echo "Enviada notificación con el texto &quot;$texto&quot.<br><br>";
}



function logit( $logger, $message, $type ) {

    $logfile = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . '/your.log';

    $logger = new Logger($logger);

    $logger->pushHandler(new StreamHandler($logfile, Logger::DEBUG));

    switch ($type) {
        case 'info':
            $logger->info($message);
            break;
        case 'error':
            $logger->error($message);
            break;
        default:
            break;
    }

    

}

?>