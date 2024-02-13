<?php

/**
 *      This script handles storing a signed document in the document vault and where to redirect to after signing
 */

add_action( "esig_signature_saved", "bs_esig_signature_saved", - 100, 1 );
/**
 * @param $args
 *
 * @return void
 */
function bs_esig_signature_saved( $args ) {
	BS_Log::info( "update_dashboard_on_sign triggered ----------" );
	// Get Document ID
	$invitation = esigget( "invitation", $args );
	$doc_id     = $invitation->document_id;
	$pdfPath    = get_signed_document_pdf_path( $doc_id );
	$inviteHash = $invitation->invite_hash;
	$checkSum   = $args['post_fields']['checksum'];
	//  Get current user ID
	$userID          = get_current_user_id();
	$userEmail       = get_userdata( $userID )->user_email;
	$userDisplayName = get_userdata( $userID )->display_name;

	[ $isTicketHolder, $isExhibitor, $isSpeaker ] = determine_user_memberships( $userID );
	BS_Log::info( "IS TICKET HOLDER: $isTicketHolder, IS EXHIBITOR: $isExhibitor, IS SPEAKER: $isSpeaker" );

	$dashboard = get_dashboard_entity( $userEmail, $userID, $args );

	$wasWaiverSigned = was_waiver_signed( $isTicketHolder, $isExhibitor, $isSpeaker, $dashboard );
	BS_Log::info( "WAS WAIVER SIGNED: $wasWaiverSigned" );

	//Attendee Waiver
	if ( $wasWaiverSigned ) {
		BS_Log::info( "IN IS TICKETHOLDER AND ATTENDEE WAIVER IN DASHBOARD NOT POPULATED" );
		create_waiver_form_entry( $userID, $userEmail, $userDisplayName );
		store_signed_waiver_in_dashboard( $pdfPath, $dashboard, $userEmail );
		store_signed_waiver_in_registration_form( $pdfPath, $userID );
	}
	//Exhibitor Contract
	if ( ! $wasWaiverSigned || empty( $userEmail ) ) {
		BS_Log::info( "IN IF EXHIBITOR WHO HASN'T SIGNED OR IF THE USER EMAIL IS EMPTY" );
		$dashboard->linkExhibitorContract = $pdfPath;
		$dashboard->hasSigned             = 'Yes';
		DashboardRepository::update( $dashboard );

		$exhibitorRegistrationEntity                       = ExhibitorOnboardingRepository::get_one_for_user( $userID );
		$exhibitorRegistrationEntity->linkToSignedContract = $pdfPath;
		BS_Log::info( "EXHIBITOR ENTITY: ", $exhibitorRegistrationEntity );

		ExhibitorOnboardingRepository::update( $exhibitorRegistrationEntity );

	}

	redirect_signer( $isSpeaker, $isExhibitor, $isTicketHolder, $userEmail, $dashboard );

	BS_Log::info( "update_dashboard_on_sign complete ----------" );

}

/**
 * @param $checkSum
 * @param $inviteHash
 * @param DashboardEntity $dashboard
 * @param string $userEmail
 *
 * @return void
 */
function store_signed_waiver_in_dashboard( $pdfPath, DashboardEntity $dashboard, string $userEmail ): void {
	$qrTicket = strval( '[gpqr]' . $userEmail . '[/gpqr]' );

	$dashboard->linkAttendeeWaiver = $pdfPath;
	$dashboard->qrCode             = do_shortcode( $qrTicket );
	DashboardRepository::update( $dashboard );
	BS_Log::info( "DASHBOARD ENTRY AFTER UPDATE WITH SIGNED WAIVER: ", $dashboard );
}

function store_signed_waiver_in_registration_form( $pdfPath, $userID ): void {
	$attendeeRegistrationFormEntry = sbFormMethods::get_gform_entry( FormIds::FORM_ID_37_REGISTER_AS_AN_ATTENDEE, $userID, 'created_by' );
	$attendeeRegistrationEntity    = new AttendeeOnboardingEntity( $attendeeRegistrationFormEntry );

	$attendeeRegistrationEntity->linkToSignedWaiver = $pdfPath;
	GFAPI::update_entry( $attendeeRegistrationEntity->formEntry );
	BS_Log::info( "ATTENDEE REGISTRATION ENTRY AFTER UPDATE WITH SIGNED WAIVER: ", $attendeeRegistrationFormEntry );
}

/**
 * @param int $userID
 * @param string $userEmail
 * @param string $userDisplayName
 *
 * @return void
 */
function create_waiver_form_entry( int $userID, string $userEmail, string $userDisplayName ): void {
//Create entry for user so checklist item will be checked
	$inputValues               = array();
	$inputValues['form_id']    = FormIds::FORM_ID_19_SIGN_THE_ATTENDEE_WAIVER;
	$inputValues['created_by'] = $userID;
	$inputValues['2']          = $userEmail;
	$inputValues['1.3']        = $userDisplayName;
	GFAPI::add_entry( $inputValues );
}

/**
 * @param bool $isSpeaker
 * @param $isExhibitor
 * @param $isTicketHolder
 * @param string $userEmail
 * @param DashboardEntity $dashboard
 *
 * @return void
 */
function redirect_signer( $isSpeaker, $isExhibitor, $isTicketHolder, $userEmail, DashboardEntity $dashboard ): void {
	BS_Log::info( "DASHBOARD ENTITY IN REDIRECT_SIGNER: ", $dashboard );
	if ( $isSpeaker ) {
		BS_Log::info( "IN REDIRECT IS SPEAKER" );
		wp_redirect( SITE_URL . '/do-items/?id=38' );

		return;
	}
	if ( $isExhibitor && $isTicketHolder && $dashboard->linkAttendeeWaiver == '' ) {
		BS_Log::info( "IN REDIRECT IF EXHIBITOR ATTENDING " );
		wp_redirect( SITE_URL . '/do-items/?id=37' );

		return;
	}
	if ( $isExhibitor && ! $isTicketHolder ) {
		BS_Log::info( "IN REDIRECT IF EXHIBITOR ATTENDING " );
		wp_redirect( SITE_URL . '/view/dashboard/entry/' . $dashboard->id );

		return;
	}
	if ( empty( $userEmail ) ) {
		BS_Log::info( "IN REDIRECT IS USER EMAIL EMPTY" );
		wp_redirect( SITE_URL . '/contract-signed-confirmation/' );

		return;
	}

	BS_Log::info( "IN REDIRECT ELSE" );
	wp_redirect( SITE_URL . '/view/dashboard/entry/' . $dashboard->id );

}

/**
 * @param $userEmail
 * @param $userID
 * @param $args
 *
 * @return DashboardEntity
 */
function get_dashboard_entity( $userEmail, $userID, $args ) {
	if ( empty( $userEmail ) ) {
		$signerEmail = $args['recipient']->user_email;
		BS_Log::info( "SIGNER EMAIL: $signerEmail" );

		$dashboard = DashboardRepository::get_one( DashboardEntity::get_form_id( 'contractSignerEmail' ), $signerEmail );
	} else {
		$dashboard = DashboardRepository::get_one_for_user( $userID );
	}
	BS_Log::info( "DASHBOARD ENTRY IN SIGNING: ", $dashboard );

	return $dashboard;
}

/**
 * @return true[]
 */
function determine_user_memberships( $userID ) {
	if ( ! empty( $userID ) ) {
		// TODO: use memberpress Adapter
		$userTransaction = MeprTransaction::get_all_by_user_id( $userID );
		$isTicketHolder  = false;
		$isExhibitor     = false;
		$isSpeaker       = false;

		foreach ( $userTransaction as $usrTxn ) {
			if ( $usrTxn->product_id == MembershipType::ATTENDEE ) {
				$isTicketHolder = true;
			}
			if ( $usrTxn->product_id == MembershipType::EXHIBITOR ) {
				$isExhibitor = true;
			}
			if ( $usrTxn->product_id == MembershipType::SPEAKER ) {
				$isSpeaker = true;
			}
		}

		return [ $isTicketHolder, $isExhibitor, $isSpeaker ];
	}
}

function was_waiver_signed( $isTicketHolder, $isExhibitor, $isSpeaker, DashboardEntity $dashboard ): bool {
	BS_Log::info( "Calculating if the waiver was signed... isTicketHolder - $isTicketHolder, isExhibitor - $isExhibitor , isSpeaker - $isSpeaker" );

	if ( ( $isTicketHolder || $isSpeaker ) && ! $isExhibitor ) {
		BS_Log::info( "Not an exhibitor: returning true..." );

		return true;
	}

	$isContractSigner    = $dashboard->isExhibitorPrimarySigningContract == 'Yes';
	$haveNotSignedWaiver = $dashboard->linkAttendeeWaiver == '';
	$hasSignedContract   = $dashboard->linkExhibitorContract !== '';

	if ( $isTicketHolder && $isExhibitor && $haveNotSignedWaiver ) {
		BS_Log::info( "Is attending exhibitor:  isContractSigner - $isContractSigner, hasSignedContract - $hasSignedContract" );

		return ! $isContractSigner || ( $isContractSigner && $hasSignedContract );
	}

	BS_Log::info( "Waiver has not been signed..." );

	return false;
}

function get_signed_document_pdf_path( $document_id ) {

	$esigSave = new ESIG_Save_Pdf();

	$pdf_name   = ESIG_PDF_Admin::instance()->pdf_file_name( $document_id ) . ".pdf";
	$pdf_buffer = ESIG_PDF_Admin::instance()->pdf_document( $document_id, $pdf_name );

	$upload_path = $esigSave->newName( $esigSave->uploadPath(), $pdf_name );    //$this->uploadPath($pdf_name);

	if ( ! @file_put_contents( $upload_path, $pdf_buffer ) ) {

		$uploadfile = @fopen( $upload_path, "w" );
		@fwrite( $uploadfile, $pdf_buffer );
		fclose( $uploadfile );
	}

	$domainWithoutProtocol = str_replace( 'https://', '', SITE_URL );
	$pdfPath               = str_replace( '/home/customer/www/' . $domainWithoutProtocol . '/public_html/', SITE_URL . '/', $upload_path );

	return $pdfPath;
}
