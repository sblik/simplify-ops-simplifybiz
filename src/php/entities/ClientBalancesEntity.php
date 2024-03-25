<?php

/**
 *
 * @property $clientUserId
 * @property $balanceAdjustmentsKey
 * @property $hoursRemaining
 * @property $hoursRemainingPendingApproval
 */
class ClientBalancesEntity extends SMPLFY_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 150;
	}

	protected function get_property_map(): array {
		return array(
			'clientUserId'                  => '3',
			'balanceAdjustmentsKey'         => '6',
			'remainingBalance'              => '5',
			'hoursRemainingPendingApproval' => '8',
		);
	}
}