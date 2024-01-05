<?php

// Update CSS within in Admin
function bs_admin_style()
{
  wp_register_style('bs-admin-styles', BS_NAME_PLUGIN_URL . 'css/admin.css');
  wp_enqueue_style('bs-admin-styles');
}
add_action('admin_enqueue_scripts', 'bs_admin_style');


function bs_frontend_style()
{
  // Front end CSS customization
  wp_register_style('bs-frontend-styles', BS_NAME_PLUGIN_URL . 'css/frontend.css');
  wp_enqueue_style('bs-frontend-styles');


  // Datepicker on submit client report page
  if (is_page(318)) :
    wp_register_script('bs-datepicker', BS_NAME_PLUGIN_URL . 'js/bs_datepicker.js');
    wp_enqueue_script('bs-datepicker');
  endif;
}
add_action('wp_enqueue_scripts', 'bs_frontend_style');