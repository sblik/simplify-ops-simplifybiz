<?php
/**
 * Form ID 34
 * PORTAL Dashboard
 */

/**
 * @property $entryId5BuyTickets
 * @property $entryId23ManageGuests
 * @property $storedCouponCode
 * @property $entryId37RegisterAttendee
 * @property $entryId26RegisterExhibitor
 * @property $entryId43InitiateExhibitorRegistration
 * @property $entryId38RegisterSpeaker
 * @property $isContactPerson
 * @property $ticketsTotal
 * @property $ticketType
 * @property $couponMax
 * @property $couponUsed
 * @property $couponBalance
 * @property $primaryNameFirst
 * @property $primaryNameLast
 * @property $exhibitorName
 * @property $linkAttendeeWaiver
 * @property $linkExhibitorContract
 * @property $qrCode
 * @property $contractSignerEmail
 * @property $invitedGuestRedeemCount
 * @property $primaryEmail
 * @property $certificateOfInsurance
 * @property $hasCertificateOfInsuranceBeenUploaded
 * @property $hasSigned
 * @property $hasSubmitted
 * @property $isDashboardForPointOfContact
 * @property $phone
 * @property $exhibitorPackage
 * @property $isExhibitorPrimarySigningContract
 */
class DashboardEntity extends BS_BaseEntity {

	public function __construct( $formEntry = array() ) {
		parent::__construct( $formEntry );
		$this->formId = FormIds::FORM_ID_34_PORTAL_DASHBOARD;
	}

	public function set_coupon_code_as_used() {
		$this->couponBalance = intval( $this->couponBalance ) - 1;
		$this->couponUsed    = intval( $this->couponUsed ) + 1;
	}

	protected function get_property_map(): array {
		return array(
			'certificateOfInsurance'                 => 28,
			'contractSignerEmail'                    => 21,
			'couponBalance'                          => 15,
			'couponMax'                              => 16,
			'couponUsed'                             => 17,
			'entryId23ManageGuests'                  => 5,
			'entryId26RegisterExhibitor'             => 7,
			'entryId37RegisterAttendee'              => 18,
			'entryId38RegisterSpeaker'               => 26,
			'entryId43InitiateExhibitorRegistration' => 20,
			'entryId5BuyTickets'                     => 13,
			'exhibitorName'                          => 9,
			'exhibitorPackage'                       => 35,
			'hasCertificateOfInsuranceBeenUploaded'  => 31,
			'hasSubmitted'                           => 33,
			'hasSigned'                              => 34,
			'invitedGuestRedeemCount'                => 27,
			'isContactPerson'                        => 10,
			'isDashboardForPointOfContact'           => 37,
			'linkAttendeeWaiver'                     => 3,
			'linkExhibitorContract'                  => 6,
			'phone'                                  => 36,
			'primaryEmail'                           => 30,
			'primaryNameFirst'                       => '11.3',
			'primaryNameLast'                        => '11.6',
			'qrCode'                                 => 4,
			'storedCouponCode'                       => 14,
			'ticketType'                             => 12,
			'ticketsTotal'                           => 19,
			'isExhibitorPrimarySigningContract'      => 40,
		);
	}
}

