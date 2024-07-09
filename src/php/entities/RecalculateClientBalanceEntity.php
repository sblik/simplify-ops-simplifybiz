<?php

/**
 *
 * @property $clientEmail
 * @property $doesWantToRedoReports
 * @property $organisationName
 */

class RecalculateClientBalanceEntity extends SMPLFY_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 163;
	}

	protected function get_property_map(): array {
		return array(
			'clientEmail'           => '1',
			'doesWantToRedoReports' => '3',
			'organisationName'      => '4',
		);
	}
}