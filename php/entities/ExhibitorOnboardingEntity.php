<?php

/**
 * Form ID 26
 *
 * @property $pointOfContactEmail
 * @property $pointOfContactFirstName
 * @property $pointOfContactLastName
 * @property $pointOfContactPhone
 * @property $isExhibitorPointOfContact
 * @property $isExhibitorAttending
 * @property $contractSignerFirstName
 * @property $contractSignerLastName
 * @property $contractSignerEmail
 * @property $exhibitorPrimaryPhone
 * @property $linkToSignedContract
 * @property $isExhibitorSigningContract
 */
class ExhibitorOnboardingEntity extends BS_BaseEntity {
	public int $ticketType = 1; //always Full Access for exhibitors

	public function __construct( $formEntry ) {
		parent::__construct( $formEntry );
		$this->formId = FormIds::FORM_ID_26_REGISTER_AS_AN_EXHIBITOR;
	}

	function is_exhibitor_primary_attending(): bool {
		return $this->isExhibitorAttending == 'Yes';
	}

	protected function get_property_map(): array {
		return array(
			'exhibitorPrimaryPhone'      => '16',
			'pointOfContactEmail'        => '15',
			'pointOfContactFirstName'    => '14.3',
			'pointOfContactLastName'     => '14.6',
			'pointOfContactPhone'        => '16',
			'isExhibitorPointOfContact'  => '73',
			'isExhibitorAttending'       => '72',
			'isExhibitorSigningContract' => '81',
			'contractSignerFirstName'    => '79.3',
			'contractSignerLastName'     => '79.6',
			'contractSignerEmail'        => '80',
			'linkToSignedContract'       => '83',
		);
	}
}