<?php

/**
 *
 * @property $employeeUserID
 * @property $devRate
 * @property $queryPeriodFrom
 * @property $queryPeriodTo
 * @property $updateForFutureSubmissionsYN
 * @property $updateForClientYN
 */
class UpdateHoursWorkedDevEntity extends SMPLFY_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 161;
	}

	protected function get_property_map(): array {
		return array(
			'employeeUserID'                => '1',
			'devRate'                       => '3',
			'queryPeriodFrom'               => '4',
			'queryPeriodTo'                 => '5',
			'$updateForFutureSubmissionsYN' => '8',
			'updateForClientYN'             => '11',
		);
	}
}