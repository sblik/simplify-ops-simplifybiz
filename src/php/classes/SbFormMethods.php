<?php

/**
 *  This class will contain methods that are used throughout the plugin to handle
 *      repeated instances of using the GFAPI to get form entry data
 *
 */
class SbFormMethods {

	/**
	 * @param $formID
	 * @param $userID
	 * @param $startDate
	 * @param $endDate
	 *
	 * @return array|WP_Error
	 */
	public static function get_entries_between_date_for_user( $formID, $userID, $startDate, $endDate ) {
		$retrieved_entry_form_id = $formID;

		$direction = 'ASC';

		// Search Form with criteria to get entry
		$retrieved_entry_search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $userID );
		$retrieved_entry_search_criteria['status']          = 'active';
		$retrieved_entry_search_criteria['start_date']      = $startDate;
		$retrieved_entry_search_criteria['end_date']        = $endDate;
		$retrieved_entry_sorting                            = array(
			'key'        => 'id',
			'direction'  => $direction,
			'is_numeric' => true,
		);
		$paging                                             = array( 'offset' => 0, 'page_size' => 999999999999 );

		return GFAPI::get_entries( $retrieved_entry_form_id, $retrieved_entry_search_criteria, $retrieved_entry_sorting, $paging );
	}
}
