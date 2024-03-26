<?php

class HandleApprovalOnWorkCompleted {
	private WorkCompletedRepository $workCompletedRepository;
	private ClientBalancesRepository $clientBalancesRepository;

	public function __construct( WorkCompletedRepository $workCompletedRepository, ClientBalancesRepository $clientBalancesRepository ) {
		$this->workCompletedRepository  = $workCompletedRepository;
		$this->clientBalancesRepository = $clientBalancesRepository;
	}

	function update_client_balances( $step_id, $entry_id, $form_id, $status ) {
		if ( $step_id != '52' ) {
			return;
		}

		SMPLFY_Log::info( "Approval step completed for entry $entry_id with status: $status" );

		$workCompletedEntry = $this->workCompletedRepository->get_one_by_id( $entry_id );
		$clientBalance      = $this->clientBalancesRepository->get_one_by_client_user_id( $workCompletedEntry->clientUserId );

		$purchasedHours   = convert_to_float( $workCompletedEntry->hoursPurchased );
		$organizationName = $workCompletedEntry->organisationName;

		if ( $status == 'approved' ) {
			SMPLFY_Log::info( "Updating client balance after work completed APPROVAL for $organizationName ($workCompletedEntry->clientUserId): ", $workCompletedEntry );

			$consumedHours = convert_to_float( $workCompletedEntry->hoursSpent );
			$hoursBalance  = convert_to_float( $clientBalance->hoursRemaining );

			$hoursNewBalance = $hoursBalance - $consumedHours + $purchasedHours;

			$clientBalance->hoursRemaining = $hoursNewBalance;
			$this->clientBalancesRepository->update( $clientBalance );

			SMPLFY_Log::info( "Number of hours pending approval updated from $hoursBalance to $hoursNewBalance for $organizationName" );

		} elseif ( $status == 'rejected' ) {
			SMPLFY_Log::info( "Updating client balance after work completed REJECTION for $organizationName ($workCompletedEntry->clientUserId): ", $workCompletedEntry );

			$pendingHours  = convert_to_float( $clientBalance->hoursRemainingPendingApproval );
			$consumedHours = convert_to_float( $clientBalance->hoursRemaining );

			$hoursNewBalance = $pendingHours + $consumedHours + $purchasedHours;

			$clientBalance->hoursRemainingPendingApproval = $hoursNewBalance;
			$this->clientBalancesRepository->update( $clientBalance );

			SMPLFY_Log::info( "Number of hours pending approval updated from $pendingHours to $hoursNewBalance for $organizationName" );
		}

		$this->bs_update_customer_closing_balance_( $workCompletedEntry );
	}

	function bs_update_customer_closing_balance_( WorkCompletedEntity $workCompletedEntry ) {
		$client_user_id = $workCompletedEntry->clientUserId;

		$purchased_hours   = convert_to_float( $workCompletedEntry->hoursPurchased );
		$purchased_minutes = convert_to_float( $workCompletedEntry->minutesPurchased );
		$consumed_hours    = convert_to_float( $workCompletedEntry->hoursSpent );
		$consumed_minutes  = convert_to_float( $workCompletedEntry->minutesSpent );

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
		SMPLFY_Log::info( 'Entries' );
		SMPLFY_Log::info( $bs_entries );

		$number_entries = count( $bs_entries );
		SMPLFY_Log::info( 'Number of Entries: ' . $number_entries );

		$bs_entry = $bs_entries[0];
		SMPLFY_Log::info( 'Entry' );
		SMPLFY_Log::info( $bs_entry );

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

		SMPLFY_Log::info( 'Hours Balance: ' . $hours_balance );
		SMPLFY_Log::info( 'Purchased (Hours): ' . $purchased_hours );
		SMPLFY_Log::info( 'Consumed (Hours): ' . $consumed_hours );
		SMPLFY_Log::info( 'New Balance (Hours)' . $hours_new_balance );

		/* ****************************************************************************
		 * OUTPUT MINUTES to log for testing
		 * **************************************************************************** */
		SMPLFY_Log::info( 'Minutes Balance: ' . $minutes_balance );
		SMPLFY_Log::info( 'Purchased (Minutes): ' . $purchased_minutes );
		SMPLFY_Log::info( 'Consumed (Minutes): ' . $consumed_minutes );
		SMPLFY_Log::info( 'New Balance (Minutes)' . $minutes_new_balance );

		/* ****************************************************************************
		 * Update THIS entry Form ID 50
		 * **************************************************************************** */
		$workCompletedEntry->minutesBroughtForward = $minutes_balance;
		$workCompletedEntry->minutesBalance        = $minutes_new_balance;
		$this->workCompletedRepository->update( $workCompletedEntry );

		/* ****************************************************************************
		 * Update entry Form ID 138 entry
		 * **************************************************************************** */
		$bs_entry_field_id_balance_hours = 6;
		GFAPI::update_entry_field( $bs_entry['id'], $bs_entry_field_id_balance_hours, $hours_new_balance );

		$bs_entry_field_id_balance_minutes = 7;
		GFAPI::update_entry_field( $bs_entry['id'], $bs_entry_field_id_balance_minutes, $minutes_new_balance );

	}
}