<?php

/**
 * A factory class responsible for creating and initializing all API controllers used in the plugin
 */
class ControllerFactory {
	public array $controllers;
	private UpdateClientBalancesHandler $updateClientBalancesHandler;

	/**
	 * @param  UpdateClientBalancesHandler  $updateClientBalancesHandler
	 */
	public function __construct( UpdateClientBalancesHandler $updateClientBalancesHandler ) {
		$this->updateClientBalancesHandler = $updateClientBalancesHandler;

		add_action( 'rest_api_init', [ $this, 'init' ] );
	}

	/**
	 * Initialize all the controllers and register their routes
	 *
	 * @return void
	 */
	public function init() {
		$this->controllers = [
			new ClientBalancesController( $this->updateClientBalancesHandler ),
		];

		foreach ( $this->controllers as $controller ) {
			$controller->register_routes();
		}
	}
}

