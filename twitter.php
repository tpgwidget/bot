<?php

require_once 'config.php';

use App\Controllers\TwitterController;
use App\Models\Twitter;

// Required Challenge Response Check
// @link https://dev.twitter.com/webhooks/securing
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['crc_token'])) {
    echo json_encode(Twitter::getChallengeResponse($_GET['crc_token']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestBody = file_get_contents('php://input');

    // Verify if the request comes from Twitter (see https://dev.twitter.com/webhooks/securing)
    $signature = $_SERVER['HTTP_X_TWITTER_WEBHOOKS_SIGNATURE'];    
    if (! Twitter::verifySignature($signature, $requestBody)) {
        header('HTTP/1.1 403 Forbidden');
        die('Invalid signature');
    }
    
    $requestBody = json_decode($requestBody);

    foreach ($requestBody->direct_message_events as $event) {
        // Only the new message that weren’t sent by the bot account
        if (($event->type === 'message_create') && ($event->message_create->sender_id !== getenv('TWITTER_ACCOUNT_ID'))) {
            TwitterController::newMessage($event);
        }
    }
}