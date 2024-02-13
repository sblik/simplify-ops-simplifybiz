<?php

class CRYO_Cvent_Contact_Links extends CRYO_Serializable {
	public CRYO_Cvent_Contact_Link $facebookUrl;
	public CRYO_Cvent_Contact_Link $twitterUrl;

	public function __construct( string $facebook_url, string $twitter_url ) {
		$this->facebookUrl = new CRYO_Cvent_Contact_Link( $facebook_url );
		$this->twitterUrl  = new CRYO_Cvent_Contact_Link( $twitter_url );
	}
}