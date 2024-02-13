<?php

class CRYO_Api {
	private static CRYO_Cvent_Api_Gateway $cvent_api_gateway;

	public static function init( CRYO_Cvent_Api_Gateway $cvent_api_gateway ) {
		self::$cvent_api_gateway = $cvent_api_gateway;
	}

	/**
	 * @param string $source_id
	 * @param string $first_name
	 * @param string $last_name
	 * @param string $email
	 * @param string $mobile_phone
	 * @param string $company
	 * @param string $title
	 * @param string $company_country
	 * @param string $company_state
	 * @param string $attendee_type (see @CRYO_Cvent_Contact_Type)
	 * @param string $admission_type (see @CRYO_Cvent_Contact_Type)
	 *
	 * @return CRYO_Create_Attendee_Response_Dto
	 * @throws Exception
	 */
	public static function create_attendee(
		string $source_id,
		string $first_name,
		string $last_name,
		string $email,
		string $mobile_phone,
		string $company,
		string $title,
		string $company_country,
		string $company_state,
		string $attendee_type,
		string $admission_type
	): CRYO_Create_Attendee_Response_Dto {
		$contact_request = new CRYO_Cvent_Create_Contact_Request(
			$first_name,
			$last_name,
			$email,
			$mobile_phone,
			$company,
			$title,
			$source_id,
			new CRYO_Cvent_Contact_Address(
				CRYO_Api_Utils::get_country_code( $company_country ),
				CRYO_Api_Utils::get_region_code( $company_state )
			),
			CRYO_Cvent_Contact_Type::from( $attendee_type )
		);

		$contact_id = self::$cvent_api_gateway->create_contact( $contact_request );

		$attendee_request    = new CRYO_Cvent_Add_Attendee_Request(
			new CRYO_Cvent_Contact( $contact_id ),
			CRYO_Cvent_Admission_Item::from( $admission_type )
		);
		$attendee_id         = self::$cvent_api_gateway->add_attendee( $attendee_request );
		$confirmation_number = self::$cvent_api_gateway->get_attendee_confirmation_number( $attendee_id );
		$response_dto        = new CRYO_Create_Attendee_Response_Dto( $attendee_id, $confirmation_number );

		BS_Log::info( 'Created attendee on Cvent:' . $response_dto->to_json_string() );

		return $response_dto;
	}
}