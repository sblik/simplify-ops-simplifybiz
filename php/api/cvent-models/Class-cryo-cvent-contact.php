<?php

class CRYO_Cvent_Contact extends CRYO_Serializable {
	public string $id;

	public function __construct( string $id ) {
		$this->id = $id;
	}
}