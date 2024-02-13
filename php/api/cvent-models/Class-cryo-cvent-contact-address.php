<?php

class CRYO_Cvent_Contact_Address extends CRYO_Serializable {

	public string $countryCode;
	public string $regionCode;

	public function __construct( string $countryCode, string $regionCode ) {
		$this->countryCode = $countryCode;
		$this->regionCode  = $regionCode;
	}
}