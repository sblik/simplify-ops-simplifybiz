<?php
/*
 * On approval of entry submitted via Form ID 50 'OPS: Submit Customer Report'
 * Update the balance
 */

add_action( 'gravityflow_step_complete', 'bs_update_customer_closing_balance_', 10, 4 );

function bs_update_customer_closing_balance_( $step_id, $entry_id, $form_id, $status ) {
	// After Gravityflow Approval Step ID 52
	if ( $step_id == '52' ) {

		/* ********************************************************************************
		 * Get THIS entry
		 * ******************************************************************************** */
		$this_entry = GFAPI::get_entry( $entry_id );

		$client_user_id        = rgar( $this_entry, '2' );
		$minutes_purchased_old =

		$purchased_hours = rgar( $this_entry, '68' );
		$purchased_hours       = floatval( str_replace( ',', '', $purchased_hours ) );

		$purchased_minutes = rgar( $this_entry, '67' );
		$purchased_minutes = intval( str_replace( ',', '', $purchased_minutes ) );

		$consumed_hours = rgar( $this_entry, '46' );
		$consumed_hours = floatval( str_replace( ',', '', $consumed_hours ) );

		$consumed_minutes = rgar( $this_entry, '66' );
		$consumed_minutes = intval( str_replace( ',', '', $consumed_minutes ) );

		BS_Log::info( 'Client User ID: ' . $client_user_id );

		/* ********************************************************************************
		 * Get current balances from Form ID 138, 'UTILITY: Reset / Track Customer Balance'
		 * ******************************************************************************** */

		$bs_form_id = 138; // UTILITY: Reset/Track Customer Balance

		$bs_search_criteria = array(
			'status'        => 'active',
			'field_filters' => array(
				'mode' => 'all',
				array(
					'key'   => '3', // Client User ID
					'value' => $client_user_id,
				),
			),
		);

		$bs_entries = GFAPI::get_entries( $bs_form_id, $bs_search_criteria );
		BS_Log::info( 'Entries' );
		BS_Log::info( $bs_entries );

		$number_entries = count( $bs_entries );
		BS_Log::info( 'Number of Entries: ' . $number_entries );

		$bs_entry = $bs_entries[0];
		BS_Log::info( 'Entry' );
		BS_Log::info( $bs_entry );

		$hours_balance = $bs_entry[6];
		$hours_balance = floatval( str_replace( ',', '', $hours_balance ) );

		$minutes_balance = $bs_entry[7];
		$minutes_balance = floatval( str_replace( ',', '', $minutes_balance ) );

		/* ****************************************************************************
		 * Calculate New Balances
		 * **************************************************************************** */
		$hours_new_balance   = $hours_balance - $consumed_hours + $purchased_hours;
		$minutes_new_balance = $minutes_balance - $consumed_minutes + $purchased_minutes;

		/* ****************************************************************************
		 * OUTPUT HOURS to log for testing
		 * **************************************************************************** */

		BS_Log::info( 'Hours Balance: ' . $hours_balance );
		BS_Log::info( 'Purchased (Hours): ' . $purchased_hours );
		BS_Log::info( 'Consumed (Hours): ' . $consumed_hours );
		BS_Log::info( 'New Balance (Hours)' . $hours_new_balance );

		/* ****************************************************************************
		 * OUTPUT MINUTES to log for testing
		 * **************************************************************************** */
		BS_Log::info( 'Minutes Balance: ' . $minutes_balance );
		BS_Log::info( 'Purchased (Minutes): ' . $purchased_minutes );
		BS_Log::info( 'Consumed (Minutes): ' . $consumed_minutes );
		BS_Log::info( 'New Balance (Minutes)' . $minutes_new_balance );

		/* ****************************************************************************
		 * Update THIS entry Form ID 50
		 * **************************************************************************** */
		$this_entry_field_id_bal_bfwd_hours = 56;
		$result                             = GFAPI::update_entry_field( $entry_id, $this_entry_field_id_bal_bfwd_hours, $hours_balance );

		$this_entry_field_id_bal_new_hours = 57;
		$result                            = GFAPI::update_entry_field( $entry_id, $this_entry_field_id_bal_new_hours, $hours_new_balance );

		$this_entry_field_id_bal_bfwd_minutes = 16;
		$result                               = GFAPI::update_entry_field( $entry_id, $this_entry_field_id_bal_bfwd_minutes, $minutes_balance );

		$this_entry_field_id_bal_new_minutes = 12;
		$result                              = GFAPI::update_entry_field( $entry_id, $this_entry_field_id_bal_new_minutes, $minutes_new_balance );

		/* ****************************************************************************
		 * Update entry Form ID 138 entry
		 * **************************************************************************** */
		$bs_entry_field_id_balance_hours = 6;
		$result                          = GFAPI::update_entry_field( $bs_entry['id'], $bs_entry_field_id_balance_hours, $hours_new_balance );

		$bs_entry_field_id_balance_minutes = 7;
		$result                            = GFAPI::update_entry_field( $bs_entry['id'], $bs_entry_field_id_balance_minutes, $minutes_new_balance );
	}
}
