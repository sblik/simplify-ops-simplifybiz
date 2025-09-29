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
        if (UserActions::does_user_have_role($user, 'tasks')) {
            return '/tasks';
        }
    }
}