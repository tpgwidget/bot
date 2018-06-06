<?php
namespace TPGwidget\Bot\Models;

/**
 * Get the current “state” of an user
 * (Useful to track the current state of a chatbot conversation)
 */
class UserState {
    //==============================================================================================
    // States
    //==============================================================================================
    const DEFAULT = 0;
    const SUBSCRIBING = 1; // The user wants to subscribe to a line (waiting for a line number)
    const UNSUBSCRIBING = 2; // The user wants to unsubscribe from a line (waiting for a line number)

    //==============================================================================================
    // Methods
    //==============================================================================================

    /**
     * Get the current state of an user
     * @param  string $userId Twitter user ID
     * @return int            The current user state (will return UserState::DEFAULT if no state is known)
     */
    public static function get($userId): int
    {
        global $db;

        $query = $db->prepare('SELECT state FROM user_states WHERE idUser = :id');
        $query->execute(['id' => $userId]);
        $userStates = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($userStates)) {
            return UserState::DEFAULT;
        }

        return (int)$userStates[0]['state'];
    }

    /**
     * Set a new state for a nuser
     * @param  string $userId   Twitter user ID
     * @param  int    $newState The new state to set
     */
    public static function set($userId, int $newState)
    {
        global $db;

        $req = $db->prepare('INSERT INTO user_states (idUser, state) VALUES(:id, :state) ON DUPLICATE KEY UPDATE state = :state, edited_at = CURRENT_TIMESTAMP()');
        $req->execute([
            'id' => $userId,
            'state' => $newState,
        ]);
    }
}
