<?php

class CRYO_Cvent_Add_Attendee_Request extends CRYO_Serializable {
	public CRYO_Cvent_Admission_Item $admissionItem;
	public CRYO_Cvent_Event $event;
	public CRYO_Cvent_Contact $contact;
	public bool $sendEmail;
	public string $status = 'Accepted';
	public bool $guest = false;
	public string $invitedBy = 'API Invited';
	public string $responseMethod = 'API-Responded';
	public string $visibility = 'Private';
	/**
	 * @var CRYO_Cvent_Attendee_Answer[]
	 */
	public array $answers;

	/**
	 *   Used for adding a new attendee in Cvent.
	 *   Based on schema from https://developer-portal.cvent.com/documentation#tag/Attendees/operation/createAttendee
	 *
	 * @param CRYO_Cvent_Contact $contact
	 * @param CRYO_Cvent_Admission_Item $admission_item
	 * @param CRYO_Cvent_Attendee_Answer[] $answers
	 * @param bool $send_email
	 * @param CRYO_Cvent_Event|null $event
	 */
	public function __construct(
		CRYO_Cvent_Contact $contact,
		CRYO_Cvent_Admission_Item $admission_item,
		array $answers = [],
		bool $send_email = false,
		CRYO_Cvent_Event $event = null
	) {
		$this->admissionItem = $admission_item;
		$this->event         = is_null( $event ) ? new CRYO_Cvent_Event() : $event;
		$this->sendEmail     = $send_email;
		$this->contact       = $contact;
		$this->answers       = $answers;
	}
}