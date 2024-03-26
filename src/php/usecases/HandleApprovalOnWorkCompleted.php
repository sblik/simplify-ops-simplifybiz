<?php

/*
 * When a work completed entry (form 50) is approved or rejected, we need to update the client balances accordingly.
 */

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

	function update_client_balances( $step_id, $entry_id, $form_id, $status ): void {
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
		$organizationName = $workCompletedEntry->organisationName;

		SMPLFY_Log::info( "Updating client balances after $status work completed for $organizationName ($workCompletedEntry->clientUserId): ", $workCompletedEntry );

		$adminClientBalance = $this->adminClientBalancesRepository->get_one_by_client_user_id( $workCompletedEntry->clientUserId );

		if ( empty( $adminClientBalance ) ) {
			SMPLFY_Log::error( "Failed to update client remaining balance: No admin client balance found for client user id: $workCompletedEntry->clientUserId" );

			return;
		}

		$purchasedHours = convert_to_float( $workCompletedEntry->hoursPurchased );

		if ( $status == 'approved' ) {

			$hoursConsumed = convert_to_float( $workCompletedEntry->hoursSpent );
			$hoursBalance  = convert_to_float( $adminClientBalance->hoursRemaining );

			$hoursNewBalance = $hoursBalance - $hoursConsumed + $purchasedHours;

			$adminClientBalance->hoursRemaining = $hoursNewBalance;
			$this->adminClientBalancesRepository->update( $adminClientBalance );

			SMPLFY_Log::info( "Admin balance: Number of hours remaining updated from $hoursBalance to $hoursNewBalance for $organizationName" );

		} elseif ( $status == 'rejected' ) {

			$hoursPending  = convert_to_float( $adminClientBalance->hoursRemainingPendingApproval );
			$hoursConsumed = convert_to_float( $adminClientBalance->hoursRemaining );

			$hoursNewBalance = $hoursPending + $hoursConsumed + $purchasedHours;

			$adminClientBalance->hoursRemainingPendingApproval = $hoursNewBalance;
			$this->adminClientBalancesRepository->update( $adminClientBalance );

			SMPLFY_Log::info( "Admin balance: Number of hours remaining pending approval updated from $hoursPending to $hoursNewBalance for $organizationName" );
		}
	}

	/**
	 * @param  WorkCompletedEntity  $workCompletedEntry
	 *
	 * @return void
	 */
	private function update_client_closing_balance( WorkCompletedEntity $workCompletedEntry ) {
		$clientBalance = $this->clientBalanceRepository->get_one_by_client_user_id( $workCompletedEntry->clientUserId );

		if ( empty( $clientBalance ) ) {
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

		SMPLFY_Log::info( 'Updating client closing balances: ', array(
			'Hours Balance'       => $hoursBalance,
			'Hours Purchased'     => $hoursPurchased,
			'Hours Consumed'      => $hoursConsumed,
			'Hours New Balance'   => $hoursNewBalance,
			'Minutes Balance'     => $minutesBalance,
			'Minutes Purchased'   => $minutesPurchased,
			'Minutes Consumed'    => $minutesConsumed,
			'Minutes New Balance' => $minutesNewBalance,
		) );

		$workCompletedEntry->minutesBroughtForward = $minutesBalance;
		$workCompletedEntry->minutesBalance        = $minutesNewBalance;
		$this->workCompletedRepository->update( $workCompletedEntry );

		$clientBalance->minutes = $minutesNewBalance;
		$clientBalance->hours   = $hoursNewBalance;
		$this->clientBalanceRepository->update( $clientBalance );

		SMPLFY_Log::info( "Closing balance updated successfully for $workCompletedEntry->organisationName and work completed id $workCompletedEntry->id" );
	}
}