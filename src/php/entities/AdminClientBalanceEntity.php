<?php

/**
 *
 * @property $clientUserId
 * @property $clientEmail
 * @property $balanceAdjustmentsKey
 * @property $currentRealBalance
 * @property $balancePendingApproval
 */
class AdminClientBalanceEntity extends SMPLFY_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 150;
	}

	protected function get_property_map(): array {
		return array(
			'clientUserId'           => '3',
			'clientEmail'            => '7',
			'balanceAdjustmentsKey'  => '6',
			'currentRealBalance'     => '5',
			'balancePendingApproval' => '8',
		);
	}
}