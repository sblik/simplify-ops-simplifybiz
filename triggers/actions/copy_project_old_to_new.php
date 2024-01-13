<?php

/*
 * FORM ID: 50
 * ACTION: IF Field ID 83 is empty, copy value of field id 39 to field id 83
 * REASON FOR ACTION: Due to client feedback, form was updated to provide more granular descriptions of projects. The
 * initial Project field was a single line text field. The new project field is a dropdown field which copies to a
 * single line text field. This single line text field now displays the project in the associated views for this form.
 *
 * */


/**
 * Fixed variables used in this script
 */


if($variables_140_util_trigger_actions['action'] == 1) {

	$sb_form_id = 50;

	$search_criteria = array(
		'status'        => 'active',
		'field_filters' => array(
			'mode' => 'active',
			array(
				'key'   => '83',
				'value' => ''
			),
			array(
			'key'   => '39',
			'operator' => 'isNot',
			'value' => '',
			)
		)
	);

	$paging          = array( 'offset' => 0, 'page_size' => 600 );

	/**
	 * Actions
	 */

	// If form id == 50,
	$sb_entries = GFAPI::get_entries( $sb_form_id, $search_criteria, null, $paging );
	BS_Log::info('$sb_entries count: ' . count($sb_entries));


	// FOR EACH ENTRY
	foreach ($sb_entries as $sb_entry) {
		// if field id 83 is empty, copy the value of field id 39 to field id 83
		BS_Log::info('Field ID 39: '. $sb_entry[39]);
		$sb_entry[83] = $sb_entry[39];
		BS_Log::info('Field ID 83: '. $sb_entry[83]);
		$result = GFAPI::update_entry( $sb_entry );
	}

}