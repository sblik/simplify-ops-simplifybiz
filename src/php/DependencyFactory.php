<?php

/**
 * A factory class responsible for creating and initializing all dependencies used in the plugin
 */
class DependencyFactory {

	/**
	 * Create and initialize all dependencies
	 *
	 * @return void
	 */
	static function create_plugin_dependencies() {
		$gravityFormsWrapper = new SMPLFY_GravityFormsApiWrapper();

		// Repositories
		$workCompletedRepository           = new WorkCompletedRepository( $gravityFormsWrapper );
		$clientBalancesRepository          = new ClientBalancesRepository( $gravityFormsWrapper );
		$clientBalanceAdjustmentRepository = new ClientBalanceAdjustmentRepository( $gravityFormsWrapper );

		// Use cases
		$updateHoursWorked             = new UpdateHoursWorked( $workCompletedRepository );
		$handleApprovalOnWorkCompleted = new HandleApprovalOnWorkCompleted( $workCompletedRepository, $clientBalancesRepository );

		// Handlers
		$updateClientBalancesHandler = new UpdateClientBalancesHandler( $workCompletedRepository, $clientBalancesRepository, $clientBalanceAdjustmentRepository );

		// Adapters
		new GravityFormsAdapter( $updateHoursWorked );
		new GravityFlowAdapter( $handleApprovalOnWorkCompleted );
		new WordPressAdapter();

		// Api
		new ControllerFactory( $updateClientBalancesHandler );
	}
}