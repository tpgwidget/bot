<?php
namespace TPGwidget\Bot\Models;

class Strings {
    public const STRINGS = [
        'actions' => [
            'goHome' => 'Retourner à l’accueil',
            'subscribe' => 'S’abonner à une ligne',
            'unsubscribe' => 'Se désabonner d’une ligne',
        ],
        'messages' => [
            "home" => "Bienvenue ! Que souhaitez-vous faire ?",
            "chooseLineToSubscribe" => "À quelle ligne voulez-vous vous abonner ?",
            "chooseLineToUnsubscribe" => "De quelle ligne voulez-vous vous désabonner ?",
            "subscribeOK" => "C’est tout bon, vous receverez maintenant un message en cas de problème sur la ligne %s !",
            "unknownRequest" => "",
        ],
    ];

    /**
     * Get a string
     * @param  string $title Name of the message (e.g. 'messages.subscribeOK')
     * @param  array  $args  Parameters to pass to printf (e.g. '14')
     * @return string        The string (e.g. 'C’est tout bon, vous receverez maintenant un message en cas de problème sur la ligne 14 !')
     */
    public static function get(string $title, array $args = []): string
    {
        $parts = explode('.', $title);

        $currentLevel = self::STRINGS;
        foreach ($parts as $level) {
            $currentLevel = $currentLevel[$level];
        }

        return vsprintf($currentLevel, $args);
    }
}
