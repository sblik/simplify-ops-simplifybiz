<?php

class CRYO_Cvent_Create_Contact_Request extends CRYO_Serializable {
	public string $firstName;
	public string $lastName;
	public string $email;
	public string $mobilePhone;
	public string $company;
	public string $title;
	public CRYO_Cvent_Contact_Type $type;
	public string $primaryAddressType;
	public CRYO_Cvent_Contact_Address $workAddress;
	public string $sourceId;

	/**
	 * Used for creating a new contact in Cvent.
	 * Based on schema from https://developer-portal.cvent.com/documentation#tag/Contacts/operation/createContacts
	 *
	 * @param string $first_name
	 * @param string $last_name
	 * @param string $email
	 * @param string $mobile_phone
	 * @param string $company
	 * @param string $title
	 * @param string $source_id
	 * @param CRYO_Cvent_Contact_Address $work_address
	 * @param CRYO_Cvent_Contact_Type $type
	 * @param string $primary_address_type
	 */
	public function __construct(
		string $first_name,
		string $last_name,
		string $email,
		string $mobile_phone,
		string $company,
		string $title,
		string $source_id,
		CRYO_Cvent_Contact_Address $work_address,
		CRYO_Cvent_Contact_Type $type,
		string $primary_address_type = 'Work'
	) {
		$this->firstName          = $first_name;
		$this->lastName           = $last_name;
		$this->email              = $email;
		$this->mobilePhone        = $mobile_phone;
		$this->company            = $company;
		$this->title              = $title;
		$this->type               = $type;
		$this->primaryAddressType = $primary_address_type;
		$this->workAddress        = $work_address;
		$this->sourceId           = $source_id;
	}
}