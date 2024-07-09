<?php

/**
 *
 * @property $clientEmail
 * @property $clientUserId
 * @property $clientFirstName
 * @property $clientLastName
 * @property $organisationName
 * @property $transactionDate
 * @property $requestSummary
 * @property $workCompleted
 * @property $hoursSpent
 * @property $project
 */
class ClientBalanceAdjustmentEntity extends SMPLFY_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId             = 151;
		$this->parentFormKey      = 150;
		$this->nestedFormFieldKey = ClientBalanceEntity::get_field_id( 'balanceAdjustmentsKey' );
	}

	protected function get_property_map(): array {
		return array(
			'clientEmail'      => '1',
			'clientUserId'     => '2',
			'clientFirstName'  => '3.3',
			'clientLastName'   => '3.6',
			'organisationName' => '4',
			'transactionDate'  => '5',
			'requestSummary'   => '6',
			'workCompleted'    => '7',
			'hoursSpent'       => '8',
			'project'          => '11',
		);
	}
}