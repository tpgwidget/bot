<?php
namespace TPGwidget\Bot\Models;

/**
 * All the messages that can be sent to the end user
 */
class Strings {
    public const STRINGS = [
        'actions' => [
            'goHome' => '🏡 Retourner à l’accueil',
            'subscribe' => '📲 S’abonner à une ligne',
            'unsubscribe' => '🛑 Se désabonner d’une ligne',
        ],
        'descriptions' => [
            'subscribe' => 'Recevoir les perturbations d’une ligne',
            'unsubscribe' => 'Arrêter les messages pour une ligne',
        ],
        'messages' => [
            'home' => "Bienvenue ! Je suis un bot Twitter qui vous tient au courant des perturbations sur vos lignes TPG favorites 🚌 🚎 🚋. \nQue souhaitez-vous faire ?",
            'chooseLineToSubscribe' => "À quelle ligne voulez-vous vous abonner ?",
            'chooseLineToUnsubscribe' => "De quelle ligne voulez-vous vous désabonner ?",
            'noLineToUnsubscribe' => 'Vous n’êtes abonné-e à aucune ligne pour l’instant ! Du coup, pas possible de se désabonner 😉',
            'invalidLineName' => 'Le nom de ligne que vous avez saisi est invalide. Veuillez taper un nom de ligne correct ou retourner à l’accueil.',

            'subscribeOK' => 'C’est tout bon, vous receverez maintenant un message en cas de problème sur la ligne %s 👌',
            'unsubscribeOK' => 'OK, vous ne serez désormais plus informé des problèmes sur la ligne %s.',
            'unknownRequest' => 'Désolé, je n’ai pas tout à fait compris votre message 😕. Je ne suis qu’un jeune bot et je fais de mon mieux ! Si vous voulez contacter mon créateur, adressez-vous à @nicolapps.',
            'noOngoingDisruptions' => 'Il n’y a aucune perturbation actuellement sur cette ligne ! 👍',
            'ongoingDisruption' => 'La perturbation suivante est actuellement en cours sur cette ligne :',
            'ongoingDisruptions' => 'Les perturbations suivantes sont actuellement en cours sur cette ligne :',
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
