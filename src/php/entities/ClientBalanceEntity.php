<?php

/**
 *
 * @property $hours
 * @property $minutes
 * @property $organization
 * @property $email
 * @property $clientUserId
 */
class ClientBalanceEntity extends SMPLFY_BaseEntity {
	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = 138;
	}

	protected function get_property_map(): array {
		return array(
			'hours'        => '6',
			'minutes'      => '7',
			'organization' => '1',
			'email'        => '4',
			'clientUserId' => '3',
		);
	}
}