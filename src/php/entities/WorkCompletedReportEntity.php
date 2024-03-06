<?php

namespace php\entities;

/**
 * Form ID 161
 *
 * @property $numberOfHoursWorked
 * @property $devRate
 * @property $consumedHours
 */
class WorkCompletedReportEntity extends BS_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 50;
	}

	protected function get_property_map(): array {
		return array(
			'numberOfHoursWorked' => '107',
			'devRate'             => '109',
			'consumedHours'       => '46',
		);
	}
}