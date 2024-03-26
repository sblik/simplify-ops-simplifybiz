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
		if ( $step_id != '52' ) {
			return;
		}

		SMPLFY_Log::info( "Approval step completed for entry $entry_id with status: $status" );

		$workCompletedEntry = $this->workCompletedRepository->get_one_by_id( $entry_id );

		$this->update_admin_client_remaining_balance( $status, $workCompletedEntry );
		$this->update_client_closing_balance( $workCompletedEntry );
	}

	/**
	 * @param $status
	 * @param  WorkCompletedEntity  $workCompletedEntry
	 *
	 * @return void
	 */
	private function update_admin_client_remaining_balance( $status, WorkCompletedEntity $workCompletedEntry ): void {
		// TODO: why are we not updating the balance adjustments (child entries) here too? (form 151)
		$organizationName   = $workCompletedEntry->organisationName;
		$adminClientBalance = $this->adminClientBalancesRepository->get_one_by_client_user_id( $workCompletedEntry->clientUserId );

		if ( empty( $adminClientBalance ) ) {
			SMPLFY_Log::error( "Failed to update client remaining balance: No admin client balance found for client user id: $workCompletedEntry->clientUserId" );

			return;
		}

		SMPLFY_Log::info( "Updating client balances after work completed ($status) for $organizationName ($workCompletedEntry->clientUserId): ", $workCompletedEntry );

		$purchasedHours = convert_to_float( $workCompletedEntry->hoursPurchased );

		if ( $status == 'approved' ) {

			$hoursConsumed = convert_to_float( $workCompletedEntry->hoursSpent );
			$hoursBalance  = convert_to_float( $adminClientBalance->hoursRemaining );

			$hoursNewBalance = $hoursBalance - $hoursConsumed + $purchasedHours;

			$adminClientBalance->hoursRemaining = $hoursNewBalance;
			$this->adminClientBalancesRepository->update( $adminClientBalance );

			SMPLFY_Log::info( "Number of hours remaining updated from $hoursBalance to $hoursNewBalance for $organizationName" );

		} elseif ( $status == 'rejected' ) {

			$hoursPending  = convert_to_float( $adminClientBalance->hoursRemainingPendingApproval );
			$hoursConsumed = convert_to_float( $adminClientBalance->hoursRemaining );

			$hoursNewBalance = $hoursPending + $hoursConsumed + $purchasedHours;

			$adminClientBalance->hoursRemainingPendingApproval = $hoursNewBalance;
			$this->adminClientBalancesRepository->update( $adminClientBalance );

			SMPLFY_Log::info( "Number of hours remaining pending approval updated $hoursPending to $hoursNewBalance for $organizationName" );
		}
	}

	/**
	 * @param  WorkCompletedEntity  $workCompletedEntry
	 *
	 * @return void
	 */
	private function update_client_closing_balance( WorkCompletedEntity $workCompletedEntry ) {
		// TODO: logging
		$clientBalance = $this->clientBalanceRepository->get_one_by_client_user_id( $workCompletedEntry->clientUserId );

		if ( empty( $adminClientBalance ) ) {
			SMPLFY_Log::error( "Failed to update client closing balance: No client balance found for client user id: $workCompletedEntry->clientUserId" );

			return;
		}

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