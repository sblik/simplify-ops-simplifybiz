<?php

class CRYO_Cvent_Event extends CRYO_Serializable {

	public string $id;

	// TODO: read this event id out of config
	public function __construct( string $id = 'c980afae-4aac-400f-be00-46d9dd4dc6a0' ) {
		$this->id = $id;
	}
}