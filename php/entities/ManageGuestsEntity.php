<?php

/**
 * Form ID 23
 * Manage Guests
 */
class ManageGuestsEntity extends BS_BaseEntity {

	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = FormIds::FORM_ID_23_MANAGE_GUESTS;
	}

	protected function get_property_map(): array {
		return array();
	}
}