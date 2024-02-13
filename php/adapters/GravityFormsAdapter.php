<?php

/**
 * Adapter for handling Gravity Forms events
 */
class GravityFormsAdapter {

	private UserRegistered $userRegistered;

	public function __construct( UserRegistered $userRegistered ) {
		$this->userRegistered = $userRegistered;

		$this->register_hooks();
		$this->register_filters();
	}

	/**
	 * Register gravity forms hooks to handle custom logic
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'gform_user_registered', [ $this->userRegistered, 'handle_user_registration' ], 10, 4 );
	}

	/**
	 * Register gravity forms filters to handle custom logic
	 *
	 * @return void
	 */
	public function register_filters() {
		add_filter( 'gpnf_enable_feed_processing_setting', '__return_true' );
	}
}