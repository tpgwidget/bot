<?php

namespace App\Models;

use Abraham\TwitterOAuth\TwitterOAuth;


class Twitter
{
    private static $lib;

    /**
     * Creates a HMAC SHA-256 hash created from the Twitter app consumer secret
     * @param  token  the token provided by the incoming GET request
     * @return string
     */
    public static function getChallengeResponse($token)
    {
        $hash = hash_hmac('sha256', $token, getenv('TWITTER_APP_CONSUMER_SECRET'), true);
        return [
            'response_token' => 'sha256=' . base64_encode($hash)
        ];
    }

    /**
     * Verifies a payload signature
     * @param  string $signature
     * @param  string $payload
     * @return bool              Is the signature correct ?
     */
    public static function verifySignature($signature, $payload)
    {
        return ($signature === 'sha256='.base64_encode(hash_hmac('sha256', $payload, getenv('TWITTER_APP_CONSUMER_SECRET'), true)));
    }

    /**
     * Get the TwitterOAuth library instance
     * @return TwitterOAuth
     */
    public static function lib()
    {
        if (!isset(self::$lib)){
            self::$lib = new TwitterOAuth(
                getenv('TWITTER_APP_CONSUMER_KEY')
                getenv('TWITTER_APP_CONSUMER_SECRET'),
                getenv('TWITTER_APP_TOKEN'),
                getenv('TWITTER_APP_SECRET')
            );
        }
        return self::$lib;
    }
}
