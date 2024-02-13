<?php

class CRYO_Create_Attendee_Response_Dto extends CRYO_Serializable {
	public string $confirmation_number;
	public string $id;

	public function __construct( string $id, string $confirmation_number ) {
		$this->id                  = $id;
		$this->confirmation_number = $confirmation_number;
	}
}