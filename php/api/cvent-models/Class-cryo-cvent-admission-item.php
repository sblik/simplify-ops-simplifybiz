<?php

class CRYO_Cvent_Admission_Item extends CRYO_Serializable {

	// TODO: read the contact type id's out of config
	public string $id;

	public function __construct( string $id ) {
		$this->id = $id;
	}

	public static function from( string $admission_type ) {
		switch ( $admission_type ) {
			case CRYO_Admission_Type::DAY_ONE:
				return CRYO_Cvent_Admission_Item::day_one();
			case CRYO_Admission_Type::DAY_TWO:
				return CRYO_Cvent_Admission_Item::day_two();
			case CRYO_Admission_Type::ALL:
				return CRYO_Cvent_Admission_Item::general_all_access();
			default:
				throw new Exception( "Invalid admission type " . print_r( $admission_type, true ) );
		}
	}

	/**
	 * @return CRYO_Cvent_Admission_Item
	 */
	public static function day_one(): CRYO_Cvent_Admission_Item {
		return new CRYO_Cvent_Admission_Item( 'f6139033-91b5-45f8-95c2-a800cafb9f58' );
	}

	/**
	 * @return CRYO_Cvent_Admission_Item
	 */
	public static function day_two(): CRYO_Cvent_Admission_Item {
		return new CRYO_Cvent_Admission_Item( '3d62fd61-873e-4a11-8b28-f756cd6424ab' );
	}

	/**
	 * @return CRYO_Cvent_Admission_Item
	 */
	public static function general_all_access(): CRYO_Cvent_Admission_Item {
		return new CRYO_Cvent_Admission_Item( '560db2fa-111a-4710-8955-bdc147995dbc' );
	}
}