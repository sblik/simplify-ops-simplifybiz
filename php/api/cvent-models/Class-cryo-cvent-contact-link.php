<?php

class CRYO_Cvent_Contact_Link extends CRYO_Serializable {
	public string $href;

	public function __construct( string $href ) {
		$this->href = $href;
	}
}