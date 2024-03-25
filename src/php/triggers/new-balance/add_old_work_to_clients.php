<?php


/**
 * Set up the endpoint
 * https://ampianhr.sblik.com/wp-json/bliksem/v1/devsimply
 */

function bs_register_webhook_endpoint() {
	register_rest_route( 'bliksem/v1', '/devsimply', array(
		'methods'  => 'POST',
		'callback' => 'bs_create_child_submissions_150',
	) );

}

add_action( 'rest_api_init', 'bs_register_webhook_endpoint' );
function bs_create_child_submissions_150() {
	SMPLFY_Log::info( "FUNCTION TRIGGERED" );
	$ops_form_id                        = 50;
	$search_criteria['field_filters'][] = array( 'key' => null, 'value' => null );
	$search_criteria['status'][]        = 'active';
	$paging                             = array( 'offset' => 0, 'page_size' => 2000 );
	$sorting                            = array( 'key' => 'id', 'direction' => 'DESC', 'is_numeric' => true );
	$ops_entries                        = GFAPI::get_entries( $ops_form_id, $search_criteria, $sorting, $paging );

	$count_ops_entries = count( $ops_entries );

	SMPLFY_Log::info( "COUNT OPS ENTRIES: " . $count_ops_entries );
	$clients_form_id                    = 150;
	$search_criteria['field_filters'][] = array( 'key' => '3', 'value' => 23 );
	$client_entries                     = GFAPI::get_entries( $clients_form_id, $search_criteria, $sorting );
	SMPLFY_Log::info( $client_entries[0] );


	/***
	 *  CLIENT 23
	 */
	for ( $i = 0; $i < $count_ops_entries; $i ++ ) {
		SMPLFY_Log::info( "CLIENT USER ID: " . $ops_entries[ $i ][2] );

		if ( $ops_entries[ $i ][2] == 23 ) {
			SMPLFY_Log::info( $client_entries[0] );

			SMPLFY_Log::info( "CLIENT ENTRY USER ID: " . $client_entries[0][2] );


			$clientsEmail     = $ops_entries[ $i ]['30'];
			$requestSummary   = $ops_entries[ $i ]['39'];
			$clientUserID     = $ops_entries[ $i ]['2'];
			$clientFirstName  = $ops_entries[ $i ]['1.3'];
			$clientLastName   = $ops_entries[ $i ]['1.6'];
			$transactionDate  = $ops_entries[ $i ]['18'];
			$organisationName = $ops_entries[ $i ]['17'];
			$workCompleted    = $ops_entries[ $i ]['70'];
			$hoursSpent       = $ops_entries[ $i ]['46'];
			$purchased_hours  = $ops_entries[ $i ]['68'];

			$new_child_entry = array(
				'form_id'                               => 151, // The ID of the child form.
				'created_by'                            => wp_get_current_user(),
				'1'                                     => $clientsEmail,
				'2'                                     => $clientUserID,
				'3.3'                                   => $clientFirstName,
				'3.6'                                   => $clientLastName,
				'4'                                     => $organisationName,
				'5'                                     => $transactionDate,
				'6'                                     => $requestSummary,
				'7'                                     => $workCompleted,
				'8'                                     => $hoursSpent,
				GPNF_Entry::ENTRY_PARENT_KEY            => $client_entries[0]['id'], // The ID of the parent entry.
				GPNF_Entry::ENTRY_PARENT_FORM_KEY       => 150, // The ID of the parent form.
				GPNF_Entry::ENTRY_NESTED_FORM_FIELD_KEY => 6, // The ID of the Nested Form field on the parent form.
			);

			$child_entry_id = GFAPI::add_entry( $new_child_entry );
		}
	}
	/***
	 *  CLIENT 27
	 */
	require BS_NAME_PLUGIN_DIR . 'includes/new-balance/add_old_work_to_client_27.php';
	bs_create_child_submissions_150_27();
	/***
	 *  CLIENT 64
	 */
	require BS_NAME_PLUGIN_DIR . 'includes/new-balance/add_old_work_to_client_64.php';
	bs_create_child_submissions_150_64();
	/***
	 *  CLIENT 88
	 */
	require BS_NAME_PLUGIN_DIR . 'includes/new-balance/add_old_work_to_client_88.php';
	bs_create_child_submissions_150_88();
	/***
	 *  CLIENT 104
	 */
	require BS_NAME_PLUGIN_DIR . 'includes/new-balance/add_old_work_to_client_104.php';
	bs_create_child_submissions_150_104();
	/***
	 *  CLIENT 105
	 */
	require BS_NAME_PLUGIN_DIR . 'includes/new-balance/add_old_work_to_client_105.php';
	bs_create_child_submissions_150_105();
	/***
	 *  CLIENT 53
	 */
	require BS_NAME_PLUGIN_DIR . 'includes/new-balance/add_old_work_to_client_53.php';
	bs_create_child_submissions_150_53();


}