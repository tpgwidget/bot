<?php
namespace TPGwidget\Bot\Models;

/**
 * Get the current “state” of an user
 */
class UserState {
    //==============================================================================================
    // States
    //==============================================================================================
    const DEFAULT = 0;

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

        return $userStates[0]['state'];
    }

    /**
     * Set a new state for a nuser
     * @param  string $userId   Twitter user ID
     * @param  int    $newState The new state to set
     */
    public static function update($userId, int $newState)
    {
        global $db;

        $req = $db->prepare('INSERT INTO user_states (idUser, state) VALUES(:id, :state) ON DUPLICATE KEY UPDATE state = :state, edited_at = CURRENT_TIMESTAMP()');
        $req->execute([
            'id' => $userId,
            'state' => $newState,
        ]);
    }
}
