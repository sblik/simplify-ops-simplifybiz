<?php

class CRYO_Cvent_Contact_Type extends CRYO_Serializable {

	// TODO: read the contact type id's out of config
	public string $id;

	public function __construct( string $id ) {
		$this->id = $id;
	}

	public static function from( string $attendee_type ) {
		switch ( $attendee_type ) {
			case CRYO_Attendee_Type::ATTENDEE:
				return CRYO_Cvent_Contact_Type::attendee();
			case CRYO_Attendee_Type::STAFF:
				return CRYO_Cvent_Contact_Type::staff();
			case CRYO_Attendee_Type::SPEAKER:
				return CRYO_Cvent_Contact_Type::speaker();
			default:
				throw new Exception( "Invalid attendee type" . print_r( $attendee_type, true ) );
		}
	}


	/**
	 * @return CRYO_Cvent_Contact_Type
	 */
	public static function attendee(): CRYO_Cvent_Contact_Type {
		return new CRYO_Cvent_Contact_Type( '04048367-a496-451f-a3c1-4daf8dd0d00f' );
	}

	/**
	 * @return CRYO_Cvent_Contact_Type
	 */
	public static function staff(): CRYO_Cvent_Contact_Type {
		return new CRYO_Cvent_Contact_Type( '8f69486d-7a84-4da6-839a-e502a6d0ba59' );
	}

	/**
	 * @return CRYO_Cvent_Contact_Type
	 */
	public static function speaker(): CRYO_Cvent_Contact_Type {
		return new CRYO_Cvent_Contact_Type( 'c47c1afe-4269-47e0-8b2a-d4a1c2b6646a' );
	}
}