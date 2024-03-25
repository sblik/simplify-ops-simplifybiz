<?php

class ControllerFactory {
	public array $controllers;

	/**
	 * Initialize all the controllers and register their routes
	 *
	 * @return void
	 */
	public function init() {
		$this->controllers = [
			new ClientBalancesController(),
		];

		foreach ( $this->controllers as $controller ) {
			$controller->register_routes();
		}
	}
}

add_action( 'rest_api_init', [ new ControllerFactory(), 'init' ] );
