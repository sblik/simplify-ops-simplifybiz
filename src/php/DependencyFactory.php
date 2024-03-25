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
		$workCompletedReportsRepository = new WorkCompletedReportsRepository( $gravityFormsWrapper );

		// Use cases
		$updateHoursWorked = new UpdateHoursWorked( $workCompletedReportsRepository );

		new GravityFormsAdapter( $updateHoursWorked );
	}
}