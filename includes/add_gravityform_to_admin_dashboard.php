<?php
/*
 * Add a gravity form to the dashboard
 * */

//  Add Form 125 'Add An Employee' to the wordpress admin dashboard

add_action('wp_dashboard_setup', 'bs_dashboard_setup');

function bs_dashboard_setup()
{
    wp_add_dashboard_widget('bs_gf_dashboard', 'Add Employee', 'bs_add_gravityform_to_admin_dashboard');
}

function bs_add_gravityform_to_admin_dashboard()
{

    // Make sure the scripts are loaded
    // http://www.gravityhelp.com/documentation/gravity-forms/extending-gravity-forms/functions/gravity_form_enqueue_scripts/
    gravity_form_enqueue_scripts(125, true);

    // Render the form
    //http://www.gravityhelp.com/documentation/gravity-forms/user-guides/getting-started/embedding-a-form/
    gravity_form(125, false, true, false, null, false);

    // Or use the shortcode if you prefer
    //echo do_shortcode( '[gravityform id=2]' );

}