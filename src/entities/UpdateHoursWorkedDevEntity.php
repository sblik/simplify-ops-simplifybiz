<?php
/**
 * Form ID 161
 *
 * @property $employeeUserID
 * @property $devRate
 * @property $queryPeriodFrom
 * @property $queryPeriodTo
 */

class UpdateHoursWorkedDevEntity extends BS_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 161;
	}

	protected function get_property_map(): array {
		return array(
			'employeeUserID'  => '1',
			'devRate'         => '3',
			'queryPeriodFrom' => '4',
			'queryPeriodTo'   => '5',
		);
	}
}