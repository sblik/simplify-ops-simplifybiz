<?php

/**
 * Form ID 37
 *
 * @property $attendeeEmail
 * @property $legalFirstName
 * @property $legalLastName
 * @property $attendeePhone
 * @property $nameAsItShouldAppearOnBadge
 * @property $organization
 * @property $state
 * @property $country
 * @property $roleInCryoIndustry
 * @property $linkToSignedWaiver
 * @property $DietaryRestrictions
 */
class AttendeeOnboardingEntity extends BS_BaseEntity {
	public function __construct( $formEntry ) {
		parent::__construct( $formEntry );
		$this->formId = FormIds::FORM_ID_37_REGISTER_AS_AN_ATTENDEE;
	}

	protected function get_property_map(): array {
		return array(
			'attendeeEmail'               => '102',
			'legalFirstName'              => '101.3',
			'legalLastName'               => '101.6',
			'attendeePhone'               => '103',
			'nameAsItShouldAppearOnBadge' => '120',
			'organization'                => '113',
			'state'                       => '115.4',
			'country'                     => '115.6',
			'roleInCryoIndustry'          => '133',
			'DietaryRestrictions'         => '117',
			'linkToSignedWaiver'          => '136',
		);
	}
}