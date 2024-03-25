<?php

class ControllerFactory {
	public array $controllers;

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
