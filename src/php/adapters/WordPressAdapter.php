<?php

/**
 * Adapter for handling WordPress events
 */
class WordPressAdapter {

	private AddUserContactMethod $addUserContactMethod;
	private MenuLoaded $menuLoaded;

	public function __construct( AddUserContactMethod $addUserContactMethod, MenuLoaded $menuLoaded ) {
		$this->addUserContactMethod = $addUserContactMethod;
		$this->menuLoaded           = $menuLoaded;

		$this->register_filters();
	}

	/**
	 * @return void
	 */
	public function register_filters() {
		add_filter( 'user_contactmethods', [ $this->addUserContactMethod, 'add_organization' ], 10, 2 );
		add_filter( 'wp_nav_menu_objects', [ $this->menuLoaded, 'add_link_to_clients_balance' ] );
	}
}