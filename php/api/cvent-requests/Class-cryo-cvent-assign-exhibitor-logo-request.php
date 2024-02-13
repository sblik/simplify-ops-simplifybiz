<?php

class CRYO_Cvent_Assign_Exhibitor_Logo_Request extends CRYO_Serializable {
	public string $event_id;
	public string $exhibitor_id;
	public string $file_id;

	/**
	 *    Used for assigning a logo to an exhibitor
	 *    https://developer-portal.cvent.com/documentation#tag/Exhibitor/operation/updateExhibitorLogo
	 *
	 * @param string $exhibitor_id
	 * @param string $file_id
	 * @param string|null $event_id
	 */
	public function __construct( string $exhibitor_id, string $file_id, string $event_id = null ) {
		$this->event_id     = is_null( $event_id ) ? ( new CRYO_Cvent_Event() )->id : $event_id;
		$this->exhibitor_id = $exhibitor_id;
		$this->file_id      = $file_id;
	}
}