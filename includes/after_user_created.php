<?php
/*
 * If role == sendUpdates, add User Organization
 * https://developer.wordpress.org/reference/hooks/profile_update/
 * */


add_action( 'profile_update', 'sb_user_updated', 10, 3 );


function sb_user_updated( $user_id, $old_user_data, $userdata ) {
    // Continue if User Role == sendupdates
//    bs('User Updated');
//    bs('User ID: ' . $user_id);
//    bs('Old User Data:');
//    bs($old_user_data);
    bs('User Data:');
    bs($userdata);

    $user_roles = $userdata['role'];

    if ( $user_roles == 'sendupdates' ) {
        //The user has the "author" role
        bs('You can continue');

        // Get user organization
        $user_email = $userdata['user_email'];
        $form_id = 53;
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
        bs('RESULT Get Form Entries');
        bs($result);
        $organization = $result[0][11];
        bs('Organization: ' . $organization);


        // If $userdata['organization] != $organization, update user organization
        $user_organization = $userdata['organization'];
        bs('User Organizatoin: ' . $user_organization);

        if($user_organization != $organization){
            $key = 'organization';
            update_user_meta( $user_id, $key, $organization );
        }
    }
}

