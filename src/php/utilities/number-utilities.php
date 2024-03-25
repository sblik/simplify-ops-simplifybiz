<?php

/**
 * @param $value
 *
 * @return float
 */
function convertToFloat( $value ): float {
	return floatval( str_replace( ',', '', $value ) );
}