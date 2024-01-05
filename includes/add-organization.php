<?php
/* Exit if accessed directly */
if (!function_exists('add_action')) {
    echo "Hi there! I'm just a plugin, not much I can do when called directly";
    exit;
}

function bs_add_user_organization($methods, $user)
{
    $methods['organization'] = 'Organization';

    return $methods;
}

add_filter('user_contactmethods', 'bs_add_user_organization', 10, 2);