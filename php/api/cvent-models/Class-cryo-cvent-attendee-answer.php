<?php

class CRYO_Cvent_Attendee_Answer {

	public CRYO_Cvent_Attendee_Question $question;
	public array $value;

	public function __construct( string $question_id, array $value ) {
		$this->question = new CRYO_Cvent_Attendee_Question( $question_id );
		$this->value    = $value;
	}
}