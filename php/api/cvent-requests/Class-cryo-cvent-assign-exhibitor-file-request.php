<?php

class CRYO_Cvent_Assign_Exhibitor_File_Request extends CRYO_Serializable {
	public string $fileId;
	public string $displayName;
	public bool $hidden = false;
	public int $order = 1;
	public CRYO_Cvent_Event $event;
	public CRYO_Cvent_Exhibitor $exhibitor;


	/**
	 *     Used for assigning a file to an exhibitor
	 *     https://developer-portal.cvent.com/documentation#tag/Exhibitor-Content/operation/updateExhibitorFile
	 *
	 * @param string $exhibitor_id
	 * @param string $file_id
	 * @param string $display_name
	 * @param string|null $event_id
	 */
	public function __construct( string $exhibitor_id, string $file_id, string $display_name, string $event_id = null ) {
		$event_id_result   = is_null( $event_id ) ? ( new CRYO_Cvent_Event() )->id : $event_id;
		$this->fileId      = $file_id;
		$this->exhibitor   = new CRYO_Cvent_Exhibitor( $exhibitor_id );
		$this->event       = new CRYO_Cvent_Event( $event_id_result );
		$this->displayName = $display_name;
	}
}