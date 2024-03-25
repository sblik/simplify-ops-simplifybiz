<?php

class RestRequestHandler {
	private $callback;

	public function __construct( callable $callback ) {
		$this->callback = $callback;
	}

	/**
	 * This is needed to ensure that we don't return a 200 response when our API handling throws an exception.
	 *
	 * @param  WP_REST_Request  $request
	 *
	 * @return mixed|WP_Error
	 */
	public function handle( WP_REST_Request $request ) {
		try {
			return call_user_func( $this->callback, $request );
		} catch ( Exception $e ) {
			return new WP_Error( 'RequestHandlingException', $e->getMessage(), array( 'status' => 500 ) );
		}
	}
}