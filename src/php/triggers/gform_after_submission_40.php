<?php
/*
 * Actions initiated by submission of Work Completed report
 * */

add_action( 'gform_after_submission_40', 'copy_project_details_old_to_new', 10, 2 );
function copy_project_details_old_to_new( $entry, $form ) {
	SMPLFY_Loginfo( 'called /triggers/gform_after_submission_40.php' );

	/**
	 * Get Variables from entry
	 */
	global $variables_50_ops_work_completed;
	$id     = rgar( $entry, 'id' );
	$action = rgar( $entry, '1' );
	/*
	 * Call scripts to process selected action
	 * */
	switch ( $action ) {
		case 1:
			SMPLFY_Loginfo( 'Task: Copy Project Details Old To New' );
			include BS_NAME_PLUGIN_DIR . 'triggers/actions/copy_project_old_to_new.php';
			break;
		default:
			SMPLFY_Loginfo( 'Task: Default' );
	}

	$result = GFAPI::delete_entry( $entry['id'] );
	SMPLFY_Loginfo( 'RESULT Delete this entry' );
	SMPLFY_Loginfo( $result );
}
