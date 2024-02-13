<?php

/**
 * Form ID 5
 * Buy Tickets
 *
 * @property $email
 * @property $firstName
 * @property $lastName
 * @property $generatedCouponCode
 * @property $isTicketForContactPerson
 * @property $totalTickets
 * @property $phone
 * @property $couponCodeUsed
 * @property $isInvited
 * @property $ticketType
 * @property $role
 */
class PurchasedTicketEntity extends BS_BaseEntity {
	public function __construct( $formEntry ) {
		parent::__construct( $formEntry );
		$this->formId = FormIds::FORM_ID_5_BUY_TICKETS;
	}

	/**
	 * Is ticket for attendee role
	 *
	 * @return bool
	 */
	function is_primary(): bool {
		return $this->role == 'attendee' && ! $this->is_speaker();
	}

	/**
	 * Does the ticket belong to a speaker
	 *
	 * @return bool
	 */
	function is_speaker(): bool {
		return strtoupper( $this->couponCodeUsed ) === AttendeeCodes::SPEAKER_CODE;
	}

	/**
	 * Does the ticket belong to a speaker
	 *
	 * @return bool
	 */
	function is_invited(): bool {
		return ! $this->is_speaker() && $this->is_guest();
	}

	/**
	 * Is ticket for guest role
	 *
	 * @return bool
	 */
	function is_guest(): bool {
		return $this->role == 'guest';
	}

	protected function get_property_map(): array {
		return array(
			'firstName'                => '26.3',
			'lastName'                 => '26.6',
			'email'                    => '27',
			'phone'                    => '28',
			'generatedCouponCode'      => '86',
			'isTicketForContactPerson' => '67',
			'totalTickets'             => '6.3',
			'couponCodeUsed'           => '70',
			'ticketType'               => '64',
			'role'                     => '83',
		);
	}
}