<?php

/**
 * Adapter for handling WordPress events
 */
class WordPressAdapter {

	private AddUserContactMethod $addUserContactMethod;

	public function __construct() {
		$this->addUserContactMethod = new AddUserContactMethod();

		$this->register_filters();
	}

	/**
	 * @return void
	 */
	public function register_filters() {
		add_filter( 'user_contactmethods', [ $this->addUserContactMethod, 'add_organization' ], 10, 2 );
	}
}