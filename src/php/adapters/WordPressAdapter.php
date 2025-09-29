<?php

/**
 * Adapter for handling WordPress events
 */
class WordPressAdapter {

	private AddUserContactMethod $addUserContactMethod;
	private MenuLoaded $menuLoaded;
	private UserLogin $userLogin;

	public function __construct( AddUserContactMethod $addUserContactMethod, MenuLoaded $menuLoaded, UserLogin $userLogin) {
		$this->addUserContactMethod = $addUserContactMethod;
		$this->menuLoaded           = $menuLoaded;
		$this->userLogin           = $userLogin;

		$this->register_filters();
	}

	/**
	 * @return void
	 */
	public function register_filters() {
        add_filter('login_redirect', [$this->userLogin, 'handle_redirect'], 10, 3);
		add_filter( 'user_contactmethods', [ $this->addUserContactMethod, 'add_organization' ], 10, 2 );
		add_filter( 'wp_nav_menu_objects', [ $this->menuLoaded, 'add_link_to_clients_balance' ] );
	}
}