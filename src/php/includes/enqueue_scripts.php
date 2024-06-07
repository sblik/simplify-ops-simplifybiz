<?php

// Update CSS within in Admin
function bs_admin_style() {
	wp_register_style( 'bs-admin-styles', BS_NAME_PLUGIN_URL . 'css/admin.css' );
	wp_enqueue_style( 'bs-admin-styles' );
}

add_action( 'admin_enqueue_scripts', 'bs_admin_style' );


function bs_frontend_style() {
	// Front end CSS customization
	wp_register_style( 'bs-frontend-styles', BS_NAME_PLUGIN_URL . 'css/frontend.css' );
	wp_enqueue_style( 'bs-frontend-styles' );

	// Add javascript file
	wp_register_script('smplfy', BS_NAME_PLUGIN_URL . 'js/smplfy.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'smplfy' );
}

add_action( 'wp_enqueue_scripts', 'bs_frontend_style' );