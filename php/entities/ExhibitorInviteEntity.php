<?php

/**
 * Form ID 43
 *
 * @property string couponName
 * @property string couponCode
 * @property int userId
 * @property string organizationName
 * @property string firstName
 * @property string lastName
 * @property string email
 * @property string packageTypeId
 * @property int quantity
 * @property string booths
 * @property string website
 * @property string paidInFull
 * @property $phone
 */
class ExhibitorInviteEntity extends BS_BaseEntity {
	public function __construct( $formEntry ) {
		parent::__construct( $formEntry );
		$this->formId = FormIds::FORM_ID_43_INITITATE_EXHIBITOR_REGISTRATION;
	}

	protected function get_property_map(): array {
		return array(
			'couponName'       => '20',
			'couponCode'       => '21',
			'userId'           => '18',
			'organizationName' => '6',
			'firstName'        => '13.3',
			'lastName'         => '13.6',
			'email'            => '7',
			'phone'            => '23',
			'packageTypeId'    => '14',
			'quantity'         => '15',
			'booths'           => '8',
			'website'          => '19',
			'paidInFull'       => '10',
		);
	}
}