<?php

class CRYO_Cvent_Exhibitor_Address extends CRYO_Serializable {

	public string $address1;
	public string $address2;
	public string $city;
	public string $region;
	public string $country;

	/**
	 * @param string $address_1
	 * @param string $address_2
	 * @param string $city
	 * @param string $region
	 * @param string $country
	 */
	public function __construct( string $address_1, string $address_2, string $city, string $region, string $country ) {
		$this->address1 = $address_1;
		$this->address2 = $address_2;
		$this->city     = $city;
		$this->region   = $region;
		$this->country  = $country;
	}
}