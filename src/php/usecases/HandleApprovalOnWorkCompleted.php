<?php

class HandleApprovalOnWorkCompleted {
	private WorkCompletedRepository $workCompletedRepository;
	private AdminClientBalanceRepository $adminClientBalancesRepository;
	private ClientBalanceRepository $clientBalanceRepository;

	public function __construct(
		WorkCompletedRepository $workCompletedRepository,
		AdminClientBalanceRepository $clientBalancesRepository,
		ClientBalanceRepository $clientBalanceRepository
	) {
		$this->workCompletedRepository       = $workCompletedRepository;
		$this->adminClientBalancesRepository = $clientBalancesRepository;
		$this->clientBalanceRepository       = $clientBalanceRepository;
	}

	/**
	 * @param $step_id
	 * @param $entry_id
	 * @param $form_id
	 * @param $status
	 *
	 * @return void
	 */
	function update_client_balances( $step_id, $entry_id, $form_id, $status ) {
		// TODO: why are we not updating the balance adjustments (child entries) here too? (form 151)
		if ( $step_id != '52' ) {
			return;
		}

		SMPLFY_Log::info( "Approval step completed for entry $entry_id with status: $status" );

		$workCompletedEntry = $this->workCompletedRepository->get_one_by_id( $entry_id );
		$adminClientBalance = $this->adminClientBalancesRepository->get_one_by_client_user_id( $workCompletedEntry->clientUserId );

		$purchasedHours   = convert_to_float( $workCompletedEntry->hoursPurchased );
		$organizationName = $workCompletedEntry->organisationName;

		if ( $status == 'approved' ) {
			SMPLFY_Log::info( "Updating client balance after work completed APPROVAL for $organizationName ($workCompletedEntry->clientUserId): ", $workCompletedEntry );

			$hoursConsumed = convert_to_float( $workCompletedEntry->hoursSpent );
			$hoursBalance  = convert_to_float( $adminClientBalance->hoursRemaining );

			$hoursNewBalance = $hoursBalance - $hoursConsumed + $purchasedHours;

			$adminClientBalance->hoursRemaining = $hoursNewBalance;
			$this->adminClientBalancesRepository->update( $adminClientBalance );

			SMPLFY_Log::info( "Number of hours pending approval updated from $hoursBalance to $hoursNewBalance for $organizationName" );

		} elseif ( $status == 'rejected' ) {
			SMPLFY_Log::info( "Updating client balance after work completed REJECTION for $organizationName ($workCompletedEntry->clientUserId): ", $workCompletedEntry );

			$hoursPending  = convert_to_float( $adminClientBalance->hoursRemainingPendingApproval );
			$hoursConsumed = convert_to_float( $adminClientBalance->hoursRemaining );

			$hoursNewBalance = $hoursPending + $hoursConsumed + $purchasedHours;

			$adminClientBalance->hoursRemainingPendingApproval = $hoursNewBalance;
			$this->adminClientBalancesRepository->update( $adminClientBalance );

			SMPLFY_Log::info( "Number of hours pending approval updated from $hoursPending to $hoursNewBalance for $organizationName" );
		}

		$this->update_customer_closing_balance( $workCompletedEntry );
	}

	function update_customer_closing_balance( WorkCompletedEntity $workCompletedEntry ) {
		// TODO: logging
		$clientBalance = $this->clientBalanceRepository->get_one_by_client_user_id( $workCompletedEntry->clientUserId );

		$hoursBalance   = convert_to_float( $clientBalance->hours );
		$hoursPurchased = convert_to_float( $workCompletedEntry->hoursPurchased );
		$hoursConsumed  = convert_to_float( $workCompletedEntry->hoursSpent );

		$hoursNewBalance = $hoursBalance - $hoursConsumed + $hoursPurchased;

		$minutesBalance   = convert_to_float( $clientBalance->minutes );
		$minutesConsumed  = convert_to_float( $workCompletedEntry->minutesSpent );
		$minutesPurchased = convert_to_float( $workCompletedEntry->minutesPurchased );

		$minutesNewBalance = $minutesBalance - $minutesConsumed + $minutesPurchased;

		$workCompletedEntry->minutesBroughtForward = $minutesBalance;
		$workCompletedEntry->minutesBalance        = $minutesNewBalance;
		$this->workCompletedRepository->update( $workCompletedEntry );

		$clientBalance->minutes = $minutesNewBalance;
		$clientBalance->hours   = $hoursNewBalance;
		$this->clientBalanceRepository->update( $clientBalance );
	}
}