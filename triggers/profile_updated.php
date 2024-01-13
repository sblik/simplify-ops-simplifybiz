<?php
/*
 * If role == sendUpdates, add User Organization
 * https://developer.wordpress.org/reference/hooks/profile_update/
 * */


add_action( 'profile_update', 'sb_user_updated', 10, 3 );


function sb_user_updated( $user_id, $old_user_data, $userdata ) {
    // Continue if User Role == sendupdates
//    BS_Log::info('User Updated');
//    BS_Log::info('User ID: ' . $user_id);
//    BS_Log::info('Old User Data:');
//    BS_Log::info($old_user_data);
    BS_Log::info('User Data:');
    BS_Log::info($userdata);

    $user_roles = $userdata['role'];

    if ( $user_roles == 'sendupdates' ) {
		include('actions/update_user_role_sendupdates.php');

    }
}

