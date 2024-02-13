<?php

class CRYO_Cvent_Create_Exhibitor_Request extends CRYO_Serializable {
	public CRYO_Cvent_Event $event;
	public string $name;
	public string $website;
	public string $mobilePhone;
	public string $description;
	public CRYO_Cvent_Contact_Links $contactLinks;
	public CRYO_Cvent_Exhibitor_Address $address;


	/**
	 *  Used for creating a new exhibitor in Cvent.
	 *  Based on schema from https://developer-portal.cvent.com/documentation#tag/Exhibitor/operation/createExhibitor
	 *
	 * @param string $company_name
	 * @param string $street_address
	 * @param string $street_address_2
	 * @param string $city
	 * @param string $region
	 * @param string $country
	 * @param string $website
	 * @param string $facebook
	 * @param string $twitter
	 * @param string $mobile_phone
	 * @param string $description
	 * @param CRYO_Cvent_Event|null $event
	 */
	public function __construct(
		string $company_name,
		string $street_address,
		string $street_address_2,
		string $city,
		string $region,
		string $country,
		string $website,
		string $facebook,
		string $twitter,
		string $mobile_phone,
		string $description,
		CRYO_Cvent_Event $event = null
	) {
		$this->address      = new CRYO_Cvent_Exhibitor_Address( $street_address, $street_address_2, $city, $region, $country );
		$this->event        = is_null( $event ) ? new CRYO_Cvent_Event() : $event;
		$this->contactLinks = new CRYO_Cvent_Contact_Links( $facebook, $twitter );
		$this->name         = $company_name;
		$this->website      = $website;
		$this->mobilePhone  = $mobile_phone;
		$this->description  = $description;
	}
}