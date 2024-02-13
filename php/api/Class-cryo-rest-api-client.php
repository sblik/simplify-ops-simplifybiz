<?php

class CRYO_Rest_Api_Client {
	private string $base_url = "https://api-platform.cvent.com/ea";

	/**
	 * @param $route
	 * @param $body
	 * @param $headers
	 *
	 * @return array|WP_Error
	 */
	public function post( $route, $body, $headers ) {
		$url  = "$this->base_url/$route";
		$args = array(
			'headers' => $headers,
			'body'    => $body,
		);
		BS_Log::info( "POST : $url", $args );

		return wp_remote_post( $url, $args );
	}

	/**
	 * @param $route
	 * @param $headers
	 *
	 * @return array|WP_Error
	 */
	public function get( $route, $headers ) {
		$url  = "$this->base_url/$route";
		$args = array(
			'headers' => $headers,
		);
		BS_Log::info( "GET : $url", $args );

		return wp_remote_get( $url, $args );
	}

	/**
	 * @param $route
	 * @param $headers
	 * @param $body
	 *
	 * @return array|WP_Error
	 */
	public function put( $route, $headers, $body = null ) {
		$url  = "$this->base_url/$route";
		$args = array(
			'method'  => 'PUT',
			'headers' => $headers,
		);
		if ( $body ) {
			$args['body'] = $body;
		}
		BS_Log::info( "PUT : $url", $args );

		return wp_remote_request( $url, $args );
	}

	/**
	 * @param $route
	 * @param $headers
	 *
	 * @return array|WP_Error
	 */
	public function delete( $route, $headers ) {
		$url  = "$this->base_url/$route";
		$args = array(
			'method'  => 'DELETE',
			'headers' => $headers,
		);
		BS_Log::info( "DELETE : $url", $args );

		return wp_remote_request( $url, $args );
	}
}