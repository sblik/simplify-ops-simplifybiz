<?php

class CRYO_Cvent_Get_Attendee_Request extends CRYO_Serializable {
	public string $filter;

	/**
	 *  Used for getting a new attendee from Cvent.
	 *  https://developer-portal.cvent.com/documentation#tag/Attendees/operation/getAttendeeById
	 *
	 * @param string $id
	 */
	public function __construct( string $id ) {
		$this->filter = "id eq '$id'";
	}
}