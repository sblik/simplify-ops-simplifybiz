<?php
/*
 * Gravityview display label of checkbox field instead of value in views
 * */

 add_filter( 'gravityview/fields/select/output_label', '__return_true' );

/*
 * Enable it for a specific Form ID
 * If you only pretend to enable the option's label in the Views associated to a certain Form ( MY_FORM_ID below) then use the following code snippet:

add_filter( 'gravityview/fields/select/output_label', 'gv_my_form_dropdown_output_label', 10, 3 );
function gv_my_form_dropdown_output_label( $show_label, $entry, $field ) {
	if( !empty( $entry['form_id'] ) && MY_FORM_ID == $entry['form_id'] ) {
		return true;
	}
	return $show_label;
}

*/



/*
 * Enable it for a specific View ID
 * If you pretend to enable the option's label in a specific View ( MY_VIEW_ID below) then use the following code snippet:

add_filter( 'gravityview/fields/select/output_label', 'gv_my_view_dropdown_output_label', 10, 3 );
function gv_my_view_dropdown_output_label( $show_label, $entry, $field ) {
	if( function_exists( 'gravityview_get_view_id' ) && MY_VIEW_ID == gravityview_get_view_id() ) {
		return true;
	}
	return $show_label;
}
*/