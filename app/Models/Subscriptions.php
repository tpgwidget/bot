<?php
namespace TPGwidget\Bot\Models;

use TPGwidget\Bot\Models\Twitter;

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
     * Validate a line name
     * @param  string      $input The user input (e.g. 'Le tram 12')
     * @return string|bool        The well-formatted line name (e.g. '12'), or false
     */
    public static function validateLineName(string $input)
    {
        $matches = [];
        $pattern = '/^(?:le |la )?(?:ligne |bus |tram )?([A-z]{1,2}|[0-9]{1,2})/i';

        if (preg_match($pattern, $input, $matches) === 0) { // No valid name found
            return false;
        }

        return strtoupper($matches[1]);
    }

    /**
     * Subscribe to a line
     * @param  string $userId Twitter user ID
     * @param  string $line   Line name
     */
    public static function subscribe($userId, $line)
    {
        global $db;

        $req = $db->prepare('INSERT INTO subscriptions(user_id, line) VALUES (?, ?)');
        $req->execute([$userId, $line]);
    }

    /**
     * Unsubscribe from a line
     * @param  string $userId Twitter user ID
     * @param  string $line   Line name
     */
    public static function unsubscribe($userId, $line)
    {
        global $db;

        $req = $db->prepare('DELETE FROM subscriptions WHERE user_id = ? AND line = ?');
        $req->execute([$userId, $line]);
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
