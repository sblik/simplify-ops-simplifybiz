<?php

add_action( 'gform_after_submission_38', 'sb_gform_after_submission_38', 10, 3 );

function sb_gform_after_submission_38( $entry, $form ) {
	BS_Log::info( "Function: sb_gform_after_submission_38()" );

	$dashboard = DashboardRepository::get_one_for_current_user();

	$dashboard->entryId38RegisterSpeaker = $entry['id'];

	DashboardRepository::update( $dashboard );
}
