<?php

class HandleApprovalOnWorkCompleted {

	private WorkCompletedRepository $workCompletedRepository;
	private ClientBalancesRepository $clientBalancesRepository;

	public function __construct( WorkCompletedRepository $workCompletedRepository, ClientBalancesRepository $clientBalancesRepository ) {
		$this->workCompletedRepository  = $workCompletedRepository;
		$this->clientBalancesRepository = $clientBalancesRepository;
	}

	function handle( $step_id, $entry_id, $form_id, $status ) {
		$approvalStepId = '52';

		if ( $step_id != $approvalStepId ) {
			return;
		}

		SMPLFY_Log::info( "Approval step completed for entry $entry_id with status: $status" );

		$workCompletedEntry = $this->workCompletedRepository->get_one_by_id( $entry_id );
		$clientBalance      = $this->clientBalancesRepository->get_one_by_client_user_id( $workCompletedEntry->clientUserId );

		$purchasedHours   = convertToFloat( $workCompletedEntry->hoursPurchased );
		$organizationName = $workCompletedEntry->organisationName;

		if ( $status == 'approved' ) {
			SMPLFY_Log::info( "Updating client balance after work completed APPROVAL for $organizationName ($workCompletedEntry->clientUserId): ", $workCompletedEntry );

			$consumedHours = convertToFloat( $workCompletedEntry->hoursSpent );
			$hoursBalance  = convertToFloat( $clientBalance->hoursRemaining );

			$hoursNewBalance = $hoursBalance - $consumedHours + $purchasedHours;

			$clientBalance->hoursRemaining = $hoursNewBalance;
			$this->clientBalancesRepository->update( $clientBalance );

			SMPLFY_Log::info( "Number of hours pending approval updated from $hoursBalance to $hoursNewBalance for $organizationName" );

		} elseif ( $status == 'rejected' ) {
			SMPLFY_Log::info( "Updating client balance after work completed REJECTION for $organizationName ($workCompletedEntry->clientUserId): ", $workCompletedEntry );

			$pendingHours  = convertToFloat( $clientBalance->hoursRemainingPendingApproval );
			$consumedHours = convertToFloat( $clientBalance->hoursRemaining );

			$hoursNewBalance = $pendingHours + $consumedHours + $purchasedHours;

			$clientBalance->hoursRemainingPendingApproval = $hoursNewBalance;
			$this->clientBalancesRepository->update( $clientBalance );

			SMPLFY_Log::info( "Number of hours pending approval updated from $pendingHours to $hoursNewBalance for $organizationName" );
		}
	}
}