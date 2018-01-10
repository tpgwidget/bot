<?php

namespace App\Models;

use App\Models\Twitter;

class Subscriptions
{
    public static function send($disruptions)
    {
        foreach ($disruptions as $disruption)
        {
            Twitter::lib()->post('direct_messages/new', [
                'user_id' => '2276497146',
                'text' => self::formatDisruption($disruption),
            ]);
        }
    }
    
    private static function formatDisruption($disruption)
    {
        return '⚠️ '.$disruption['nature']. ' (ligne '.$disruption['lineCode'].')'
            .PHP_EOL.PHP_EOL.(trim($disruption['place']) !== '' ? trim($disruption['place']).' – ' : '').$disruption['consequence'];
    }
}