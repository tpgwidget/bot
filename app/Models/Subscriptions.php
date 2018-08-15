<?php
namespace TPGwidget\Bot\Models;

use TPGwidget\Bot\Models\{Disruptions, Twitter, UserState};

/**
 * Manages the subscriptions (from an user to a TPG line)
 */
class Subscriptions
{
    /**
     * Send a disruption
     * @param  mixed[] $disruptions Disruptions data
     */
    public static function send($disruptions)
    {
        global $db;

        foreach ($disruptions as $disruption)
        {
            $content = Disruptions::format($disruption);

            $query = $db->prepare('SELECT user_id FROM subscriptions WHERE line = ?');
            $query->execute([$disruption['lineCode']]);
            $subscribers = $query->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($subscribers as $subscriber) {
                UserState::set($subscriber['user_id'], UserState::DEFAULT);
                Twitter::message($content)
                    ->withDefaultActions()
                    ->sendTo($subscriber['user_id']);
            }
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
        $pattern = '/^(?:de |à )?(?:le |la )?(?:ligne |bus |tram )?([A-z]{1,2}|[0-9]{1,2})/i';

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
     * Get all the lines an user is subscribed to
     * @param  string $userId The Twitter user ID
     * @return array          All the lines
     */
    public static function getSubscriptionsFrom($userId): array
    {
        global $db;

        $lines = [];

        $req = $db->prepare('SELECT line FROM subscriptions WHERE user_id = ? ORDER BY line');
        $req->execute([$userId]);
        $subscriptions = $req->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($subscriptions as $subscription) {
            $lines[] = $subscription['line'];
        }

        return $lines;
    }
}
