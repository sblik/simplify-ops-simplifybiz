<?php

/*
 * On submission of Form ID 140 'Client Entry Maintenance'
 * Execute Tasks
 * */

add_action('gform_after_submission_140', 'gform_after_submission_140_function', 10, 2);
function gform_after_submission_140_function($entry, $form)
{
    bs('gform_after_submission_140_function');

    /* ************************************************************************************
     * GET VARIABLES
     * ************************************************************************************ */
    $task = rgar($entry, 5);

    /* ************************************************************************************
     * Process Tasks
     * ************************************************************************************ */

    switch ($task) {
        case 1:
            bs('Task: Copy Old to New');
            include BS_NAME_PLUGIN_DIR . 'gform-140/convert_old_to_new.php';

            break;
        case 2:
            bs('Task: Update Email Address');
            include BS_NAME_PLUGIN_DIR . 'gform-140/update_organization_email.php';
            break;
    }

    /* ************************************************************************************
     * Delete $this entry
     * ************************************************************************************ */
    $entry_id = $entry['id'];
    $result = GFAPI::delete_entry($entry_id);

}
