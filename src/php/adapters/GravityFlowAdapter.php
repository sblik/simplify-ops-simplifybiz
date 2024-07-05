<?php

/**
 * Adapter for handling Gravity Flow events
 */
class GravityFlowAdapter {

	private HandleApprovalOnWorkCompleted $handleApprovalOnWorkCompleted;

	public function __construct( HandleApprovalOnWorkCompleted $handleApprovalOnWorkCompleted ) {
		$this->handleApprovalOnWorkCompleted    = $handleApprovalOnWorkCompleted;

		$this->register_hooks();
	}

	/**
	 * Register gravity flow hooks to handle custom logic
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'gravityflow_step_complete', [ $this->handleApprovalOnWorkCompleted, 'update_client_balances' ], 10, 4 );
	}
}