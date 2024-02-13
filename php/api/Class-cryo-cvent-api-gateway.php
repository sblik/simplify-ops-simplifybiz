<?php

class CRYO_Cvent_Api_Gateway {

	private CRYO_Rest_Api_Client $rest_api_client;
	private CRYO_Cvent_Session_Manager $session_manager;

	public function __construct( CRYO_Rest_Api_Client $rest_api_client, CRYO_Cvent_Session_Manager $session_manager ) {
		$this->rest_api_client = $rest_api_client;
		$this->session_manager = $session_manager;
	}

	/**
	 * Creates a new contact on Cvent
	 * ref: https://developer-portal.cvent.com/documentation#tag/Contacts/operation/createContacts
	 *
	 * @param CRYO_Cvent_Create_Contact_Request $contact_request
	 *
	 * @return string (contact id)
	 * @throws Exception
	 */
	public function create_contact( CRYO_Cvent_Create_Contact_Request $contact_request ): string {
		$response = $this->rest_api_client->post(
			"contacts",
			$contact_request->to_json_array(),
			$this->get_headers()
		);

		$http_code = CRYO_Api_Utils::get_http_code( $response );
		$json_body = CRYO_Api_Utils::get_json_body( $response );
		$result    = $json_body[0];

		if ( $http_code !== 207 || $result->status !== 201 ) {
			$body = CRYO_Api_Utils::get_string_body( $response );
			throw new Exception( "Unable to create contact '$contact_request->email' in Cvent. Response code: $http_code. Response body:\n$body" );
		}

		$contact_id = $result->data->id;
		BS_Log::info( "Created contact '$contact_request->email' in Cvent: $contact_id" );

		return $contact_id;
	}

	private function get_headers( $content_type = 'application/json' ): array {
		$bearer_token = $this->session_manager->get_bearer_token();

		$headers = array(
			'Authorization' => "Bearer $bearer_token",
		);

		if ( $content_type ) {
			$headers['Content-Type'] = $content_type;
		}

		return $headers;
	}

	/**
	 * Adds a new attendee on Cvent
	 * ref: https://developer-portal.cvent.com/documentation#tag/Attendees/operation/createAttendee
	 *
	 * @param CRYO_Cvent_Add_Attendee_Request $attendee_request
	 *
	 * @return string (attendee id)
	 * @throws Exception
	 */
	public function add_attendee( CRYO_Cvent_Add_Attendee_Request $attendee_request ): string {
		$response = $this->rest_api_client->post(
			"attendees",
			$attendee_request->to_json_array(),
			$this->get_headers()
		);

		$http_code = CRYO_Api_Utils::get_http_code( $response );
		$json_body = CRYO_Api_Utils::get_json_body( $response );
		$result    = $json_body[0];

		if ( $http_code !== 207 || $result->status !== 200 ) {
			$body = CRYO_Api_Utils::get_string_body( $response );
			throw new Exception( "Unable to add attendee for contact '{$attendee_request->contact->id}' in Cvent. Response code: $http_code. Response body:\n$body" );
		}

		$attendee_id = $result->data->id;
		BS_Log::info( "Added attendee for contact '{$attendee_request->contact->id}' in Cvent: $attendee_id" );

		return $attendee_id;
	}

	/**
	 * Creates a new exhibitor on Cvent
	 * ref: https://developer-portal.cvent.com/documentation#tag/Exhibitor/operation/createExhibitor
	 *
	 * @param CRYO_Cvent_Create_Exhibitor_Request $exhibitor_request
	 *
	 * @return string (exhibitor id)
	 * @throws Exception
	 */
	public function create_exhibitor( CRYO_Cvent_Create_Exhibitor_Request $exhibitor_request ): string {
		$response = $this->rest_api_client->post(
			"events/{$exhibitor_request->event->id}/exhibitors",
			$exhibitor_request->to_json(),
			$this->get_headers()
		);

		$body      = CRYO_Api_Utils::get_string_body( $response );
		$http_code = CRYO_Api_Utils::get_http_code( $response );

		if ( $http_code !== 201 ) {
			throw new Exception( "Unable to create exhibitor '$exhibitor_request->name' in Cvent. Response code: $http_code. Response body:\n$body" );
		}

		$body         = CRYO_Api_Utils::get_json_body( $response );
		$exhibitor_id = $body->id;
		BS_Log::info( "Created exhibitor '$exhibitor_request->name' in Cvent: $exhibitor_id" );

		return $exhibitor_id;
	}

	/**
	 * Assigns a logo to an exhibitor on Cvent
	 * ref: https://developer-portal.cvent.com/documentation#tag/Exhibitor/operation/updateExhibitorLogo
	 *
	 * @param CRYO_Cvent_Assign_Exhibitor_Logo_Request $assign_logo_request
	 *
	 * @return void
	 * @throws Exception
	 */
	public function assign_exhibitor_logo_image( CRYO_Cvent_Assign_Exhibitor_Logo_Request $assign_logo_request ): void {
		$exhibitor_id = $assign_logo_request->exhibitor_id;
		$file_id      = $assign_logo_request->file_id;
		$event_id     = $assign_logo_request->event_id;
		$route        = "events/$event_id/exhibitors/$exhibitor_id/logo-files/$file_id";

		$response = $this->rest_api_client->put(
			$route,
			$this->get_headers( null )
		);

		$body      = CRYO_Api_Utils::get_string_body( $response );
		$http_code = CRYO_Api_Utils::get_http_code( $response );

		if ( $http_code !== 200 ) {
			throw new Exception( "Unable to assign logo '$file_id' to exhibitor '$exhibitor_id' in Cvent. Response code: $http_code. Response body:\n$body" );
		}

		BS_Log::info( "Assigned logo '$file_id' to exhibitor '$exhibitor_id' in Cvent" );
	}

	/**
	 * Adds a file on Cvent
	 * ref: https://developer-portal.cvent.com/documentation#tag/File/operation/uploadFile
	 *
	 * @param $file_path
	 *
	 * @return string
	 * @throws Exception
	 */
	public function add_file( $file_path ): string {
		$boundary = CRYO_Api_Utils::create_multi_form_boundary();
		$body     = CRYO_Api_Utils::create_multi_part_form_body( $file_path, $boundary );
		$headers  = $this->get_headers( "multipart/form-data; boundary=$boundary" );

		$response  = $this->rest_api_client->post(
			"files",
			$body,
			$headers
		);
		$http_code = CRYO_Api_Utils::get_http_code( $response );

		if ( $http_code !== 201 ) {
			$response_body = CRYO_Api_Utils::get_string_body( $response );
			throw new Exception( "Error adding file to Cvent. File path: '$file_path'. Response code: $http_code. Response body:\n$response_body" );
		}

		$response_body = CRYO_Api_Utils::get_json_body( $response );
		$file_id       = $response_body->id;
		BS_Log::info( "Added file '$file_path' in Cvent. File id: $file_id" );

		return $file_id;
	}

	/**
	 * @param string $id
	 *
	 * @return string
	 * @throws Exception
	 */
	public function get_attendee_confirmation_number( string $id ): string {
		$attendee = $this->get_attendee( $id );
		BS_Log::info( "Got attendee '$id' confirmation number from Cvent: $attendee->confirmationNumber" );

		return $attendee->confirmationNumber;
	}

	/**
	 * Gets attendee by id from Cvent
	 * ref: https://developer-portal.cvent.com/documentation#tag/Attendees/operation/listAttendeesPostFilter
	 *
	 * @param string $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function get_attendee( string $id ) {
		$request  = new CRYO_Cvent_Get_Attendee_Request( $id );
		$response = $this->rest_api_client->post(
			"attendees/filter",
			$request->to_json(),
			$this->get_headers()
		);

		$body      = CRYO_Api_Utils::get_string_body( $response );
		$http_code = CRYO_Api_Utils::get_http_code( $response );

		if ( $http_code !== 200 ) {
			throw new Exception( "Unable to get attendee $id in Cvent. Response code: $http_code. Response body:\n$body" );
		}
		$body = CRYO_Api_Utils::get_json_body( $response );

		if ( empty( $body->data ) ) {
			throw new Exception( "Unable to get attendee $id in Cvent. Request was successful but no attendee was found matching id" );
		}

		return $body->data[0];
	}


	/**
	 *  Associates a file with an exhibitor on Cvent
	 *  ref: https://developer-portal.cvent.com/documentation#tag/Exhibitor-Content/operation/updateExhibitorFile
	 *
	 * @param CRYO_Cvent_Assign_Exhibitor_File_Request $assign_file_request
	 *
	 * @return void
	 * @throws Exception
	 */
	public function assign_exhibitor_file( CRYO_Cvent_Assign_Exhibitor_File_Request $assign_file_request ): void {
		$exhibitor_id = $assign_file_request->exhibitor->id;
		$file_id      = $assign_file_request->fileId;
		$event_id     = $assign_file_request->event->id;
		$route        = "events/$event_id/exhibitors/$exhibitor_id/files/$file_id";

		$response = $this->rest_api_client->put(
			$route,
			$this->get_headers(),
			$assign_file_request->to_json()
		);

		$body      = CRYO_Api_Utils::get_string_body( $response );
		$http_code = CRYO_Api_Utils::get_http_code( $response );

		if ( $http_code !== 200 ) {
			throw new Exception( "Unable to assign file '$file_id' to exhibitor '$exhibitor_id' in Cvent. Response code: $http_code. Response body:\n$body" );
		}

		BS_Log::info( "Assigned file '$file_id' to exhibitor '$exhibitor_id' in Cvent" );
	}
}