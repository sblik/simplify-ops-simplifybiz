<?php

/**
 * @property $clientUserId
 * @property $clientEmail
 * @property $requestSummary
 * @property $clientFirstName
 * @property $clientLastName
 * @property $transactionDate
 * @property $organisationName
 * @property $workCompleted
 * @property $minutesSpent
 * @property $minutesBroughtForward
 * @property $hoursSpent
 * @property $minutesPurchased
 * @property $minutesBalance
 * @property $hoursPurchased
 * @property $numberOfHoursWorked
 * @property $devRate
 * @property $fiftySix
 * @property $fiftySeven
 */
class WorkCompletedEntity extends SMPLFY_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 50;
	}

	protected function get_property_map(): array {
		return array(
			'clientUserId'          => '2',
			'clientEmail'           => '30',
			'requestSummary'        => '39',
			'clientFirstName'       => '1.3',
			'clientLastName'        => '1.6',
			'minutesBroughtForward' => '16',
			'minutesBalance'        => '12',
			'transactionDate'       => '18',
			'organisationName'      => '17',
			'workCompleted'         => '70',
			'hoursPurchased'        => '68',
			'hoursSpent'            => '46',
			'minutesPurchased'      => '67',
			'minutesSpent'          => '66',
			'reportType'            => '53',
			'numberOfHoursWorked'   => '107',
			'devRate'               => '109',
		);
	}
}