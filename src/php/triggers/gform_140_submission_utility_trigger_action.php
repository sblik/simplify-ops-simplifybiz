<?php
/*
 * Perform utility actions on database
 *
 * */

add_action( 'gform_after_submission_140', 'sb_gform_after_submission_140', 10, 2 );
function sb_gform_after_submission_140( $entry, $form ) {
	SMPLFY_Loginfo( 'called /triggers/gform_140_submission_utility_trigger_action.php' );

	/**
	 * Get Variables from entry
	 */
	$variables_140_util_trigger_actions = array(
		"id"     => rgar( $entry, 'id' ),
		"action" => rgar( $entry, '1' ),
	);

	SMPLFY_Loginfo( '$variables_140_util_trigger_actions: ' );
	SMPLFY_Loginfo( $variables_140_util_trigger_actions );

	/*
	 * CALL ACTIONS
	 * */
	switch ( $variables_140_util_trigger_actions['action'] ) {
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
