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
		$clientBalanceRepository           = new ClientBalanceRepository( $gravityFormsWrapper );
		$clientBalanceAdjustmentRepository = new ClientBalanceAdjustmentRepository( $gravityFormsWrapper );

		// Use cases
		$updateHoursWorked             = new UpdateHoursWorked( $workCompletedRepository, $clientBalanceRepository );
		$workReportCompleted           = new WorkReportSubmitted( $workCompletedRepository, $clientBalanceRepository );
		$handleApprovalOnWorkCompleted = new HandleApprovalOnWorkCompleted( $workCompletedRepository, $clientBalanceRepository, $clientBalanceAdjustmentRepository );
		$recalculateClientBalance      = new RecalculateClientBalance( $workCompletedRepository, $clientBalanceRepository, $clientBalanceAdjustmentRepository );
		$menuLoaded                    = new MenuLoaded( $clientBalanceRepository );
		$addUserContactMethod          = new AddUserContactMethod();
		$userLogin          = new UserLogin();
		$workReportApproved          = new WorkReportApproved();

		// Handlers
		$updateClientBalancesHandler = new UpdateClientBalancesHandler( $workCompletedRepository, $clientBalanceRepository, $clientBalanceAdjustmentRepository );

		// Adapters
		new GravityFormsAdapter( $updateHoursWorked, $workReportCompleted, $recalculateClientBalance );
		new GravityFlowAdapter( $handleApprovalOnWorkCompleted, $workReportApproved );
		new WordPressAdapter( $addUserContactMethod, $menuLoaded,$userLogin );
		new MemberpressAdapter( $userLogin);

		// Api
		new ControllerFactory( $updateClientBalancesHandler );
	}
}