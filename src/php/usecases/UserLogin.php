<?php

class UserLogin
{
    public function __construct()
    {

    }

    /**
     * Entry method for the use case to handle a user registration.
     *
     * @param $userId
     * @param $feed
     * @param $entry
     *
     * @return void
     */
    public function handle_redirect($redirect_to, $user): string
    {
        SMPLFY_Log::info("In handle redirect, ", $redirect_to);
        SMPLFY_Log::info("Does user have tasks role: ", self::does_user_have_role($user, 'tasks'));
        if (self::does_user_have_role($user, 'tasks')) {
            return '/tasks';
        }
        SMPLFY_Log::info("after condition");
        return $redirect_to;
    }
    public static function does_user_have_role( $user, $roleName ): bool {
        foreach ( $user->caps as $role => $true ) {
            if ( $role == $roleName ) {
                return true;
            }
        }

        return false;
    }
}