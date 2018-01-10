<?php

namespace App\Controllers;

use App\Models\Twitter;

class TwitterController
{
    public static function newMessage($message)
    {
/*
        Twitter::lib()->post('direct_messages/new', [
            'user_id' => $message->message_create->sender_id,
            'text' => 'Message bien reçu ! 😀'
        ]);
*/
        // error_log(json_encode($message, JSON_PRETTY_PRINT), 3, __DIR__.'/../../log/twitter.log');
/*
        Twitter::lib()->user_request([
            'method' => 'POST',
            'url'    => Twitter::lib()->url('1.1/direct_messages/events/new'),
            'params' => [
                'event' => [
                    'type' => 'message_create',
                    'message_create' => [
                        'target' => [
                            'recipient_id' => $message->message_create->sender_id,
                        ],
                        'message_data' => [
                            'text' => 'Test',
                        ],
                    ],
                ],
            ],
        ]);
*/

/*
        Twitter::lib()->user_request([
            'method' => 'POST',
              'url' => Twitter::lib()->url('1.1/statuses/update'),
              'params' => array(
                'status' => $message->message_create->sender_id
              )
        ]);
*/

/*
        Twitter::lib()->request('POST', '/1.1/direct_messages/events/new', json_encode([
            'event' => [
                'type' => 'message_create',
                'message_create' => [
                    'target' => [
                        'recipient_id' => $message->message_create->sender_id,
                    ],
                    'message_data' => [
                        'text' => 'Test',
                    ],
                ],
            ],
        ]), true, false, ['Content-Type' => 'application/json']);
*/
    }
}
