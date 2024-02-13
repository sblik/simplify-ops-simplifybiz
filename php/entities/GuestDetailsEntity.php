<?php

/**
 * Form ID 8
 *
 * @property string legalFirstName
 * @property string legalLastName
 * @property string contactPersonId
 * @property int ticketType
 * @property string email
 * @property string phone
 */
class GuestDetailsEntity extends BS_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = FormIds::FORM_ID_8_GUEST_DETAILS_REPEATER;
	}

	protected function get_property_map(): array {
		return array(
			'legalFirstName'  => '101.3',
			'legalLastName'   => '101.6',
			'contactPersonId' => '126',
			'ticketType'      => '125',
			'email'           => '102',
			'phone'           => '103',
		);
	}
}