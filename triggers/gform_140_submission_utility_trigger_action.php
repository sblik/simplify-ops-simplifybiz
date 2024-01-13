<?php
/*
 * Perform utility actions on database
 *
 * */

add_action('gform_after_submission_140', 'sb_gform_after_submission_140', 10, 2);
function sb_gform_after_submission_140($entry, $form)
{
	BS_Log::info('called /triggers/gform_140_submission_utility_trigger_action.php');

	/**
	 * Get Variables from entry
	 */
	global $variables_140_util_trigger_actions;
	include('get-entry-variables/variables_form_id_140_utilities_trigger_action.php');

	BS_Log::info('$variables_140_util_trigger_actions: ');
	BS_Log::info($variables_140_util_trigger_actions);

	/*
	 * CALL ACTIONS
	 * */
	switch($variables_140_util_trigger_actions['action']) {
		case 1:
			BS_Log::info('Task: Copy Project Details Old To New');
			include BS_NAME_PLUGIN_DIR . 'triggers/actions/copy_project_old_to_new.php';
			break;
		default:
		BS_Log::info('Task: Default');
	}

	$result = GFAPI::delete_entry($entry['id']);
	BS_Log::info('RESULT Delete this entry');
	BS_Log::info($result);
}
