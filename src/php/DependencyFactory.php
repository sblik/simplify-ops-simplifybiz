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
		$workCompletedRepository                = new WorkCompletedRepository( $gravityFormsWrapper );
		$adminClientBalanceRepository           = new AdminClientBalanceRepository( $gravityFormsWrapper );
		$adminClientBalanceAdjustmentRepository = new AdminClientBalanceAdjustmentRepository( $gravityFormsWrapper );

		// Use cases
		$updateHoursWorked             = new UpdateHoursWorked( $workCompletedRepository );
		$workReportCompleted           = new WorkReportSubmitted( $workCompletedRepository, $adminClientBalanceRepository );
		$handleApprovalOnWorkCompleted = new HandleApprovalOnWorkCompleted( $workCompletedRepository, $adminClientBalanceRepository, $clientBalanceRepository );

		// Handlers
		$updateClientBalancesHandler = new UpdateClientBalancesHandler( $workCompletedRepository, $adminClientBalanceRepository, $adminClientBalanceAdjustmentRepository );

		// Adapters
		new GravityFormsAdapter( $updateHoursWorked, $workReportCompleted );
		new GravityFlowAdapter( $handleApprovalOnWorkCompleted );
		new WordPressAdapter();

		// Api
		new ControllerFactory( $updateClientBalancesHandler );
	}
}