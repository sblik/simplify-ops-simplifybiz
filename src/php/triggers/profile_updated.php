<?php
/*
 * If role == sendUpdates, add User Organization
 * https://developer.wordpress.org/reference/hooks/profile_update/
 * */


add_action( 'profile_update', 'sb_user_updated', 10, 3 );


function sb_user_updated( $user_id, $old_user_data, $userdata ) {
	// Continue if User Role == sendupdates
//    SMPLFY_Loginfo('User Updated');
//    SMPLFY_Loginfo('User ID: ' . $user_id);
//    SMPLFY_Loginfo('Old User Data:');
//    SMPLFY_Loginfo($old_user_data);
	SMPLFY_Loginfo( 'User Data:' );
	SMPLFY_Loginfo( $userdata );

	$user_roles = $userdata['role'];

	if ( $user_roles == 'sendupdates' ) {
		include( 'actions/update_user_role_sendupdates.php' );

	}
}

