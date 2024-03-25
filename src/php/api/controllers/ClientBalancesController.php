<?php

class ClientBalancesController extends BaseController {
	public function __construct() {
		$this->controllerNamespace = 'bliksem/v1';
	}

	/**
	 * @return void
	 */
	public function register_routes() {
		$this->register_rest_route( '/devsimply', 'POST', [ $this, 'update_client_balances' ] );
	}

	/**
	 * @param  WP_REST_Request  $request
	 *
	 * @return void
	 * @throws Exception
	 */
	public function update_client_balances( WP_REST_Request $request ) {
		SMPLFY_Log::info( "ClientBalancesController: Received request to update client balances", $request );
		$handler = new UpdateClientBalancesHandler();
		$handler->handle();
	}
}