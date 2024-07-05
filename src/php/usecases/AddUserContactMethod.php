<?php

/**
 * Used to add the organization contact method to the user contact methods
 */

class AddUserContactMethod {
	public function add_organization( $methods, $user ) {
		SMPLFY_Log::info( "Adding organization to user contact methods for $user->ID" );

		$methods['organization'] = 'Organization';

		return $methods;
	}
}