<?php
/*
 * On approval of entry submitted via Form ID 50 'OPS: Submit Customer Report'
 * Update the balance
 */

add_action('gravityflow_step_complete', 'bs_update_customer_closing_balance_2', 10, 4);

function bs_update_customer_closing_balance_2($step_id, $entry_id, $form_id, $status)
{
    // After Gravityflow Approval Step ID 52
    if ($step_id == '52') {

        bs("STATUS: ");
        bs($status);
    if($status == 'approved'){
        /* ********************************************************************************
         * Get THIS entry
         * ******************************************************************************** */
        $this_entry = GFAPI::get_entry($entry_id);

        $client_user_id = rgar($this_entry, '2');

        $purchased_hours = rgar($this_entry, '68');
        $purchased_hours = floatval(str_replace(',', '', $purchased_hours));

        $purchased_minutes = rgar($this_entry, '67');
        $purchased_minutes = intval(str_replace(',', '', $purchased_minutes));

        $consumed_hours = rgar($this_entry, '46');
        $consumed_hours = floatval(str_replace(',', '', $consumed_hours));

        $consumed_minutes = rgar($this_entry, '66');
        $consumed_minutes = intval(str_replace(',', '', $consumed_minutes));

        bs('Client User ID: ' . $client_user_id);

        /* ********************************************************************************
         * Get current balances from Form ID 138, 'UTILITY: Reset / Track Customer Balance'
         * ******************************************************************************** */   

//Get client's organisation entry
    $clients_form_id = 150;
    $search_criteria['field_filters'][] = array('key' => '3', 'value' => $client_user_id);
    $search_criteria['status'][] = 'active';
    $sorting = array('key' => 'id', 'direction' => 'DESC', 'is_numeric' => true);
    $client_entries = GFAPI::get_entries($clients_form_id, $search_criteria, $sorting);

        $hours_balance = $client_entries[0][5];
        $hours_balance = floatval(str_replace(',', '', $hours_balance));
        $pending_hours_balance = $client_entries[0][8];
        $pending_hours_balance = floatval(str_replace(',', '', $pending_hours_balance));

        /* ****************************************************************************
         * Calculate New Balances
         * **************************************************************************** */
        $hours_new_balance = $hours_balance - $consumed_hours + $purchased_hours;
    
        /* ****************************************************************************
         * Update THIS entry Form ID 50
         * **************************************************************************** */
        $this_entry_field_id_bal_bfwd_hours = 56;
        $result = GFAPI::update_entry_field($entry_id, $this_entry_field_id_bal_bfwd_hours, $hours_balance);

        $this_entry_field_id_bal_new_hours = 57;
        $result = GFAPI::update_entry_field($entry_id, $this_entry_field_id_bal_new_hours, $hours_new_balance);

        /* ****************************************************************************
         * Update entry Form ID 138 entry
         * **************************************************************************** */
        $bs_entry_field_id_balance_hours = 5;
        $result = GFAPI::update_entry_field($client_entries[0]['id'], $bs_entry_field_id_balance_hours, $hours_new_balance);
    }
    else if($status == 'rejected'){
        $this_entry = GFAPI::get_entry($entry_id);

        bs("IN STATUS REJECTED");
        $client_user_id = rgar($this_entry, '2');
        //Get client's organisation entry
        $clients_form_id = 150;
        $search_criteria['field_filters'][] = array('key' => '3', 'value' => $client_user_id);
        $search_criteria['status'][] = 'active';
        $sorting = array('key' => 'id', 'direction' => 'DESC', 'is_numeric' => true);
        $client_entries = GFAPI::get_entries($clients_form_id, $search_criteria, $sorting);

        /**
         * Remove hours from pending amount
         */
        $pendingHours = $client_entries[0][8];

        $purchased_hours = rgar($this_entry, '68');
        $purchased_hours = floatval(str_replace(',', '', $purchased_hours));

        $consumed_hours = rgar($this_entry, '46');
        $consumed_hours = floatval(str_replace(',', '', $consumed_hours));

        $hours_new_balance = $pendingHours + $consumed_hours + $purchased_hours;

        $bs_entry_field_id_balance_hours = 8;
        $result = GFAPI::update_entry_field($client_entries[0]['id'], $bs_entry_field_id_balance_hours, $hours_new_balance);


    }

}
}
