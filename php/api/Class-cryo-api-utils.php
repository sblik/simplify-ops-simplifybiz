<?php

class CRYO_Api_Utils {
	/**
	 * Get the body of a REST response as a JSON object
	 *
	 * @param $response
	 *
	 * @return mixed
	 */
	public static function get_json_body( $response ) {
		$body = wp_remote_retrieve_body( $response );

		return json_decode( $body );
	}

	/**
	 * Get the body of a REST response as a string
	 *
	 * @param $response
	 *
	 * @return string
	 */
	public static function get_string_body( $response ): string {
		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Get the http code of a REST response
	 *
	 * @param $response
	 *
	 * @return int|string
	 */
	public static function get_http_code( $response ) {
		return wp_remote_retrieve_response_code( $response );
	}

	/**
	 * @param string $file_path
	 * @param string $boundary
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function create_multi_part_form_body( string $file_path, string $boundary ): string {
		$file_data = CRYO_Api_Utils::get_file_contents( $file_path );

		$body = "--$boundary" . NEW_LINE;
		$body .= 'Content-Disposition: form-data; name="file"; filename="' . basename( $file_path ) . '"' . NEW_LINE;
		$body .= 'Content-Type: application/octet-stream' . NEW_LINE;
		$body .= 'Content-Transfer-Encoding: base64' . NEW_LINE . NEW_LINE;
		$body .= $file_data . NEW_LINE;
		$body .= "--$boundary--";

		error_log( "Multi part form body:" . print_r( $body, true ) );

		return $body;
	}

	/**
	 * @param $file_path
	 *
	 * @return string
	 * @throws Exception
	 */
	private static function get_file_contents( $file_path ): string {
		$file_contents = file_get_contents( $file_path );
		if ( ! $file_contents ) {
			throw new Exception( "Unable to read file contents from '$file_path'" );
		}

		return base64_encode( $file_contents );
	}

	/**
	 * @return string
	 */
	public static function create_multi_form_boundary(): string {
		return md5( time() );
	}

	/**
	 * Returns the ISO 3166-1 alpha-2 code for the supplied country name
	 *
	 * @param string $country_name
	 *
	 * @return string
	 */
	public static function get_country_code( string $country_name ): string {
		return GFCommon::get_country_code( $country_name );
	}

	/**
	 * @param string $company_state
	 *
	 * @return string
	 */
	public static function get_region_code( string $company_state ): string {
		$us_states      = GFCommon::get_us_states();
		$is_valid_state = in_array( $company_state, $us_states );

		return $is_valid_state ? GFCommon::get_us_state_code( $company_state ) : '';
	}
}