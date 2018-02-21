<?php

namespace App\Models;

use App\Models\Twitter;

class Subscriptions
{
    /**
     * Send a disruption
     * @param  mixed[] $disruptions Disruptions data
     */
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

    /**
     * Format a disruption text
     * @param  mixed[] $disruption Disruption data
     * @return string              Disruption text
     */
    private static function formatDisruption(array $disruption)
    {
        // Header (line and nature)
        $text = '⚠️ '.$disruption['nature']. ' (ligne '.$disruption['lineCode'].')'.PHP_EOL.PHP_EOL;

        // Place
        $text .= (trim($disruption['place']) !== '' ? trim($disruption['place']).' – ' : '');

        // Content
        $text .= $disruption['consequence'];

        return $text;
    }
}
