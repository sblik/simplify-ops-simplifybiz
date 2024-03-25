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
 * @property $hoursSpent
 * @property $hoursPurchased
 */
class WorkCompletedEntity extends SMPLFY_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 50;
	}

	protected function get_property_map(): array {
		return array(
			'clientUserId'     => '2',
			'clientEmail'      => '30',
			'requestSummary'   => '39',
			'clientFirstName'  => '1.3',
			'clientLastName'   => '1.6',
			'transactionDate'  => '18',
			'organisationName' => '17',
			'workCompleted'    => '70',
			'hoursSpent'       => '46',
			'hoursPurchased'   => '68',
		);
	}
}