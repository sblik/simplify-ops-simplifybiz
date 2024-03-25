<?php

abstract class BaseController {
	protected string $controllerNamespace;

	abstract function register_routes();

	function register_rest_route( string $route, string $method, callable $callback ) {

		register_rest_route( $this->controllerNamespace, $route, array(
			'methods'  => $method,
			'callback' => function ( $request ) use ( $callback ) {
				$handler = new RestRequestHandler( $callback );

				return $handler->handle( $request );
			},
		) );
	}
}