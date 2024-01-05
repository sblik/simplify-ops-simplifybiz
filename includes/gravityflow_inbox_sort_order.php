<?php

/**
 * Reverse the order of the entries in the inbox.
 *
 * @param array $sorting
 *
 * @return array
 */
function sh_gravityflow_inbox_sorting( $sorting ) {
	return array( 'direction' => 'ASC' );
}

add_filter( 'gravityflow_inbox_sorting', 'sh_gravityflow_inbox_sorting', 10 );