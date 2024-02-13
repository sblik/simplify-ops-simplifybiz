<?php

/**
 * Adapter for handling Gravity View Events
 */
class GravityViewAdapter {

	private ReassignTicket $reassignTicket;

	public function __construct( ReassignTicket $reassignTicket ) {
		$this->reassignTicket = $reassignTicket;

		$this->register_hooks();
	}

	/**
	 * Register gravity view hooks to handle custom logic
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'gravityview/edit_entry/before_update', [
			$this->reassignTicket,
			'set_current_ticket_holder_email',
		], 10, 3 );

		add_action( 'gravityview/edit_entry/after_update', [ $this->reassignTicket, 'reassign_ticket', ], 10, 3 );
	}
}