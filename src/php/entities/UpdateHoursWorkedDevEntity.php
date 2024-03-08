<?php

/**
 *
 * @property $employeeUserID
 * @property $devRate
 * @property $queryPeriodFrom
 * @property $queryPeriodTo
 * @property $updateDevRateMetaYN
 * @property $clientEmail
 * @property $updateForClientYN
 * @property $clientOrg
 */
class UpdateHoursWorkedDevEntity extends BS_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 161;
	}

	protected function get_property_map(): array {
		return array(
			'employeeUserID'      => '1',
			'devRate'             => '3',
			'queryPeriodFrom'     => '4',
			'queryPeriodTo'       => '5',
			'updateDevRateMetaYN' => '8',
			'clientEmail'         => '9',
			'updateForClientYN'   => '11',
		);
	}
}