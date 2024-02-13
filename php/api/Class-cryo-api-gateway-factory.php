<?php

class CRYO_Api_Gateway_Factory {
	/**
	 * @return CRYO_Cvent_Api_Gateway
	 */
	public static function create(): CRYO_Cvent_Api_Gateway {
		$rest_api_client = new CRYO_Rest_Api_Client();
		$session_manager = new CRYO_Cvent_Session_Manager( $rest_api_client );

		return new CRYO_Cvent_Api_Gateway( $rest_api_client, $session_manager );
	}
}