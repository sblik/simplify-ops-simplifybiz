<?php

/**
 * Convert a string number to a float
 *
 * @param $value
 *
 * @return float
 */
function convert_to_float( $value ): float {
	return floatval( str_replace( ',', '', $value ) );
}