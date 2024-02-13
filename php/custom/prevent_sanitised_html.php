<?php
/**
 * @param $buffer
 *
 * @return array|string|string[]
 *
 *  When generating a QR code in a script, it sanitises the HTML preventing it from being parsed, this converts it back to html
 */
function callback( $buffer ) {
	$buffer = str_replace( '&amp;', '&', $buffer );
	$buffer = str_replace( '&gt;', '>', $buffer );
	$buffer = str_replace( '&quot;', '"', $buffer );
	$buffer = str_replace( '&lt;', '<', $buffer );

	return $buffer;
}

function buffer_start() {
	ob_start( "callback" );
}

function buffer_end() {
	ob_end_flush();
}

add_action( 'wp_head', 'buffer_start' );
add_action( 'wp_footer', 'buffer_end' );

