<?php

class ClientBalancesController extends BaseController {
	private UpdateClientBalancesHandler $updateClientBalancesHandler;

	public function __construct(
		UpdateClientBalancesHandler $updateClientBalancesHandler
	) {
		$this->controllerNamespace         = 'bliksem/v1';

		$this->updateClientBalancesHandler = $updateClientBalancesHandler;
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
		SMPLFY_Log::info( "ClientBalancesController: Received request to create client balances adjustments", $request );

		$this->updateClientBalancesHandler->handle();
	}
}