<?php
/**
 * Actions to take if user role includes 'sendupdates'
 */

// Get user organization
$user_email = $userdata['user_email'];
$form_id = $gform_ids['mng'];
$search_criteria = array(
	'status'        => 'active',
	'field_filters' => array(
		'mode' => 'active',
		array(
			'key'   => '2',
			'value' => $user_email
		)
	)
);

$result = GFAPI::get_entries( $form_id, $search_criteria );
BS_Log::info('RESULT Get Form Entries');
BS_Log::info($result);
$organization = $result[0][11];
BS_Log::info('Organization: ' . $organization);


// If $userdata['organization] != $organization, update user organization
$user_organization = $userdata['organization'];
BS_Log::info('User Organizatoin: ' . $user_organization);

if($user_organization != $organization){
	$key = 'organization';
	update_user_meta( $user_id, $key, $organization );
}