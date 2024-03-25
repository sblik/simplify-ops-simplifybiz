<?php

class AddUserContactMethod {
	public function add_organization( $methods, $user ) {
		SMPLFY_Log::info( "Adding organization to user contact methods for $user->ID" );

		$methods['organization'] = 'Organization';

		return $methods;
	}
}