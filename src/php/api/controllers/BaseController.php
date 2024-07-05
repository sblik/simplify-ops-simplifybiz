<?php

abstract class BaseController {
	protected string $controllerNamespace;

	abstract function register_routes();

	/**
	 * Register a REST route with the WordPress REST API
	 * Wrap the callback in a handler to catch exceptions
	 *
	 * @param  string  $route
	 * @param  string  $method
	 * @param  callable  $callback
	 *
	 * @return void
	 */
	function register_rest_route( string $route, string $method, callable $callback ) {

		// TODO: add authorization using permission_callback option
		register_rest_route( $this->controllerNamespace, $route, array(
			'methods'  => $method,
			'callback' => function ( $request ) use ( $callback ) {
				$handler = new RestRequestHandler( $callback );

				return $handler->handle( $request );
			},
		) );
	}
}