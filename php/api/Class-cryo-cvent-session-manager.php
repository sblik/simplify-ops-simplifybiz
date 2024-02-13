<?php

class CRYO_Cvent_Session_Manager {

	private string $bearer_token_transient_key = 'cryo_cvent_bearer_token';
	private string $client_id;
	private string $api_credentials;
	private CRYO_Rest_Api_Client $rest_api_client;

	public function __construct( CRYO_Rest_Api_Client $rest_api_client ) {
		$this->rest_api_client = $rest_api_client;
		$this->api_credentials = $this->get_api_credentials();
	}

	/**
	 * @return string
	 */
	private function get_api_credentials(): string {
		// TODO: read these credentials from settings

		$this->client_id = '0oaw76mu8plyCO0QB1t7';
		$client_secret   = '_7MWRa8AFF8eTlDOkVaeljsnrBir3TyhZKcpoNIBqxyhVn0ttZja50Ti-1BvUekU';

		return base64_encode( "$this->client_id:$client_secret" );
	}

	/**
	 * @return string (bearer token)
	 * @throws Exception
	 */
	public function get_bearer_token(): string {
		$stored_bearer_token = $this->retrieve_bearer_token();
		sleep( 5 ); // TODO: remove this when we have a better way of handling rate limiting

		if ( $this->validate_session( $stored_bearer_token ) ) {
			return $stored_bearer_token;
		}

		return $this->create_session();
	}

	private function retrieve_bearer_token(): string {
		return get_transient( $this->bearer_token_transient_key );
	}

	/**
	 * @param string $stored_bearer_token
	 *
	 * @return bool
	 */
	private function validate_session( string $stored_bearer_token ): bool {
		if ( empty( $stored_bearer_token ) ) {
			BS_Log::info( "Cvent Session has expired." );

			return false;
		}

		return true;
	}

	/**
	 * @return string (bearer token)
	 * @throws Exception
	 */
	private function create_session(): string {
		BS_Log::info( "Creating a new Cvent session..." );

		$body = http_build_query(
			array(
				'grant_type' => 'client_credentials',
				'client_id'  => $this->client_id,
			)
		);

		$headers = array(
			'Authorization' => "Basic $this->api_credentials",
			'Content-Type'  => 'application/x-www-form-urlencoded',
		);

		$response = $this->rest_api_client->post(
			"oauth2/token",
			$body,
			$headers
		);

		$http_code = wp_remote_retrieve_response_code( $response );

		if ( $http_code !== 200 ) {
			$body          = CRYO_Api_Utils::get_string_body( $response );
			$error_message = "Unable to create session token. Response code: $http_code. Response body:\n$body";
			throw new Exception( $error_message );
		}

		$bearer_token = CRYO_Api_Utils::get_json_body( $response )->access_token;
		$this->store_bearer_token( $bearer_token );

		return $bearer_token;
	}

	/**
	 * @param string $bearer_token
	 *
	 * @return void
	 */
	private function store_bearer_token( string $bearer_token ): void {
		set_transient( $this->bearer_token_transient_key, $bearer_token, HOUR_IN_SECONDS - 10 );
	}
}