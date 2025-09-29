<?php

class MemberpressAdapter
{
    private UserLogin $userLogin;

    public function __construct(UserLogin $userLogin)
    {
        $this->userLogin = $userLogin;

        $this->register_hooks();
        $this->register_filters();
    }

    /**
     * Register gravity forms hooks to handle custom logic
     *
     * @return void
     */
    public function register_hooks()
    {

    }

    /**
     * Register gravity forms filters to handle custom logic
     *
     * @return void
     */
    public function register_filters()
    {
        add_filter('mepr-process-login-redirect-url', [$this->userLogin, 'handle_redirect'], 11, 2);

    }
}