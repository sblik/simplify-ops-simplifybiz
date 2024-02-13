<?php

/**
 *  This class will contain methods that are used throughout the plugin to handle
 *      repeated instances of using the GFAPI to get form entry data
 *
 */

class SbFormMethods {

	/**
	 *  Get gravity form entry
	 */
	public static function get_gform_entry( $formID, $fieldValue, $fieldId ) {

		$retrieved_entry_form_id = $formID;

		// Search Form with criteria to get entry
		$retrieved_entry_search_criteria['field_filters'][] = array( 'key' => $fieldId, 'value' => $fieldValue );
		$retrieved_entry_search_criteria['status']          = 'active';
		$retrieved_entry_sorting                            = array(
			'key'        => 'id',
			'direction'  => 'ASC',
			'is_numeric' => true,
		);
		$retrieved_entry_entries                            = GFAPI::get_entries( $retrieved_entry_form_id, $retrieved_entry_search_criteria, $retrieved_entry_sorting );

		if ( ! empty( $retrieved_entry_entries ) ) {
			return $retrieved_entry_entries[0];
		}

		return null;
	}

	/**
	 *  Get gravity form entries
	 */
	public static function get_gform_entries( $formID, $searchID, $searchField, $direction = null ) {

		$retrieved_entry_form_id = $formID;
		if ( $direction == null ) {
			$direction = 'ASC';
		}
		// Search Form with criteria to get entry
		$retrieved_entry_search_criteria['field_filters'][] = array( 'key' => $searchField, 'value' => $searchID );
		$retrieved_entry_search_criteria['status']          = 'active';
		$retrieved_entry_sorting                            = array(
			'key'        => 'id',
			'direction'  => $direction,
			'is_numeric' => true,
		);
		$paging                                             = array( 'offset' => 0, 'page_size' => 999999999999 );
		$retrieved_entry_entries                            = GFAPI::get_entries( $retrieved_entry_form_id, $retrieved_entry_search_criteria, $retrieved_entry_sorting, $paging );

		return $retrieved_entry_entries;
	}
}
