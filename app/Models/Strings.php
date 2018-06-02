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
            'home' => "Bienvenue ! Que souhaitez-vous faire ?",
            'chooseLineToSubscribe' => "À quelle ligne voulez-vous vous abonner ?",
            'chooseLineToUnsubscribe' => "De quelle ligne voulez-vous vous désabonner ?",
            'invalidLineName' => 'Le nom de ligne que vous avez saisi est invalide. Veuillez taper un nom de ligne correct ou retourner à l’accueil.',

            'subscribeOK' => 'C’est tout bon, vous receverez maintenant un message en cas de problème sur la ligne %s !',
            'unsubscribeOK' => 'OK, vous ne serez désormais plus informé des problèmes sur la ligne %s.',
            'unknownRequest' => 'Désolé, je n’ai pas tout à fait compris votre message.',
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
