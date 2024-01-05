<?php

/**
 *  This script will trigger upon completion of the client report submission form
 *      it will:
 *          - Find the client the work was completed for's "Clients" form submission
 *          - It will then submit a repeater in that form with details submitted in the submission that triggered the script
 */
add_filter('gpnf_child_entry_max', function ($child_entry_max) {
    return 999;
});
add_action('gform_after_submission_50', 'bs_create_child_submission_150', 10, 2);
function bs_create_child_submission_150($entry, $form)
{

    //Client's Details
    $clientUserID = rgar($entry, '2');
    $clientsEmail = rgar($entry, '31');
    $clientFirstName = rgar($entry, '1.3');
    $clientLastName = rgar($entry, '1.6');
    $organisationName = rgar($entry, '17');
    //Work completed
    $requestSummary = rgar($entry, '39');
    $workCompleted = rgar($entry, '70');
    $hoursSpent = rgar($entry, '46');
    $purchased_hours = rgar($entry, '68');
    //Get client's organisation entry
    $clients_form_id = 150;
    $search_criteria['field_filters'][] = array('key' => '3', 'value' => $clientUserID);
    $search_criteria['status'][] = 'active';
    $sorting = array('key' => 'id', 'direction' => 'DESC', 'is_numeric' => true);
    $client_entries = GFAPI::get_entries($clients_form_id, $search_criteria, $sorting);
    bs("CLIENTS ENTRY: ");
    bs($client_entries[0]);
    //Hours in their balance
    $currentHoursBalance = $client_entries[0]['5'];
    //Pending Hours
    $pendingHoursBalance = $client_entries[0]['8'];

    //Take away the work completed from the balance
    $updatedBalance = $pendingHoursBalance - $hoursSpent + $purchased_hours;

    /**
     * Check whether to create an entry for client in "Clients" (form 150)
     */
    if ($client_entries[0] == '') {
        bs("INSIDE IF CLIENT ENTRY EMPTY");
        $new_client_entry = array(
            'form_id' => 150,
            'created_by' => wp_get_current_user(),
            '7' => $clientsEmail,
            '3' => $clientUserID,
            '1' => $organisationName,
            '8' => $updatedBalance,
            '5' => 0,
            '10' => $purchased_hours,
        );
        $clients_entry_id = GFAPI::add_entry($new_client_entry);
        /**
         * Submit Work Completed Child Form
         */
        $new_child_entry = array(
            'form_id' => 151, // The ID of the child form.
            'created_by' => wp_get_current_user(),
            '1' => $clientsEmail,
            '2' => $clientUserID,
            '3.3' => $clientFirstName,
            '3.6' => $clientLastName,
            '4' => $organisationName,
            '5' => date("m-d-Y"),
            '6' => $requestSummary,
            '7' => $workCompleted,
            '8' => $hoursSpent,
            '9' => $updatedBalance,
            GPNF_Entry::ENTRY_PARENT_KEY => $clients_entry_id, // The ID of the parent entry.
            GPNF_Entry::ENTRY_PARENT_FORM_KEY => 150, // The ID of the parent form.
            GPNF_Entry::ENTRY_NESTED_FORM_FIELD_KEY => 6, // The ID of the Nested Form field on the parent form.
        );
        $child_entry_id = GFAPI::add_entry($new_child_entry);
    } else {
        bs("IN ELSE");
        /**
         * Submit Work Completed Child Form
         */
        $new_child_entry = array(
            'form_id' => 151, // The ID of the child form.
            'created_by' => wp_get_current_user(),
            '1' => $clientsEmail,
            '2' => $clientUserID,
            '3.3' => $clientFirstName,
            '3.6' => $clientLastName,
            '4' => $organisationName,
            '5' => date("m-d-Y"),
            '6' => $requestSummary,
            '7' => $workCompleted,
            '8' => $hoursSpent,
            '9' => $updatedBalance,
            GPNF_Entry::ENTRY_PARENT_KEY => $client_entries[0]['id'], // The ID of the parent entry.
            GPNF_Entry::ENTRY_PARENT_FORM_KEY => 150, // The ID of the parent form.
            GPNF_Entry::ENTRY_NESTED_FORM_FIELD_KEY => 6, // The ID of the Nested Form field on the parent form.
        );
        $child_entry_id = GFAPI::add_entry($new_child_entry);
        bs("NEW CHILD ENTRY: ");
        bs($new_child_entry);
        /**
         * Update Hours Balance
         */
        //Update client's entry with new balance
        $client_entries[0]['8'] = $updatedBalance;
        //Update amount purchased
        // Add new amount purchased to previous
        $client_entries[0]['10'] = $client_entries[0]['10'] + $purchased_hours;

        $updated_entry = GFAPI::update_entry($client_entries[0]);
    }
}
