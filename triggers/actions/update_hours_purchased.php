<?php
/*
 * Purchase of Hours
 * Triggered on submission of form 63, purchase of hours
 */

add_action('gform_post_payment_completed', 'bs_purchased_hours_update_hours_balance', 10, 2);

function bs_purchased_hours_update_hours_balance($entry, $action)
{
    $bs_entry_form_id = rgar($entry, 'form_id');

    if ($bs_entry_form_id == 63) {
        
        // Obtain Quantities Purchased in form  Submission
        $bs_hours_purchased_details = array(
            'client_first_name' => rgar($entry, '16.3'),
            'client_last_name' => rgar($entry, '16.6'),
            'client_user_id' => rgar($entry, '49'),
            'client_email_address' => rgar($entry, '18'),
            'organization' => rgar($entry, '20'),
            'qty_hours_purchased_hourly' => rgar($entry, '2'),
            'qty_hours_purchased_plus' => rgar($entry, '47'),
        );
        
        // If existing client, store client user_id from form
       
        // Submitted ID if new user
        $bs_user_id = '';
        if ($bs_hours_purchased_details['client_user_id'] =='') {
            $user = get_user_by('email', $bs_hours_consumed_details['client_user_email']);
            $bs_user_id = $user->ID;
        } else {
            $bs_user_id = $bs_hours_purchased_details['client_user_id'];
        }

        // If new client, obtain newly created client user id from user table
        if (!isset($bs_user_id) || empty($bs_user_id)) {
            // Get user id by email
            $bs_user = get_user_by('email', $bs_hours_purchased_details['client_email_address']);
            $bs_user_id = $bs_user->ID;
        }

        
        // CONVERT HOURS PURCHASED TO MINUTES
        $bs_hours_purchased = '';
        if ($bs_hours_purchased_details['qty_hours_purchased_hourly'] != '') {
            $bs_hours_purchased = $bs_hours_purchased_details['qty_hours_purchased_hourly'];
        } else {
            $bs_hours_purchased = $bs_hours_purchased_details['qty_hours_purchased_plus'];
        }

        $bs_minutes_purchased = $bs_hours_purchased * 60;
      

        // Variables
        $hours_balance_form_id = 50;
        

        /* GET PREVIOUS BALANCE IF EXISTS
         ************************************************************************* */
        // Get Entries
        $search_criteria = array(
        'status' => 'active',
        'field_filters' => array(
            'mode' => 'any',
            array(
                'key' => '17', // Client User ID
                'value' => $bs_hours_purchased_details['organization'],
            ),
        ),
    );
        $sorting = array('key' => 'id', 'direction' => 'DESC', 'is_numeric' => true);

        $entries = GFAPI::get_entries($hours_balance_form_id, $search_criteria, $sorting);

        $number_entries = count($entries);

        $old_balance = '';
        $new_balance = '';

        if ($number_entries != 0) {
            // Get Most Recent Entry
            $entry = GFAPI::get_entry($entries[0]['id']);
            $old_balance = $entry['12'];
        } else {
            $old_balance = 0;
        }

        // Obtain Balance
        $new_balance = $old_balance + $bs_minutes_purchased;

        /* PROCESS HOURS WERE CONSUMED
         ************************************************************************* */

        // CLIENT DETAILS

        $input_values = [
        'form_id' =>$hours_balance_form_id,
        '2' => $bs_hours_purchased_details['client_user_id'],// User ID
        '1.3' => $bs_hours_purchased_details['client_first_name'], // Firstname
        '1.6' => $bs_hours_purchased_details['client_last_name'], // Lastname
        '17' => $bs_hours_purchased_details['organization'], // Lastname

        // MINUTES TRANSACTIONS
        '16' => $old_balance, // Balance B/Fwd
        '10' => $bs_minutes_purchased, // Purchased (minutes)
        '11' => 0, // Used (Minutes)
        '12' => $new_balance, // Balance (Minutes)
    ];
    
        
        /* PROCESS ENTRY
         ************************************************************************* */
        $entry_id = GFAPI::add_entry($input_values);
                

        /* PROCESS NOTIFICATIONS
         ************************************************************************* */
        $form = GFAPI::get_form($hours_balance_form_id);
        $entry = GFAPI::get_entry($entry_id);
        GFAPI::send_notifications($form, $entry);
    }
}