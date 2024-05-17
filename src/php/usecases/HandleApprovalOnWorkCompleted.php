<?php

/*
 * When a work completed entry (form 50) is approved or rejected, we need to update the client balances accordingly.
 */

class HandleApprovalOnWorkCompleted {
	private WorkCompletedRepository $workCompletedRepository;
	private AdminClientBalanceRepository $adminClientBalancesRepository;

	public function __construct(
		WorkCompletedRepository $workCompletedRepository,
		AdminClientBalanceRepository $clientBalancesRepository
	) {
		$this->workCompletedRepository       = $workCompletedRepository;
		$this->adminClientBalancesRepository = $clientBalancesRepository;
	}

	function update_client_balances( $step_id, $entry_id, $form_id, $status ): void {
		if ( $step_id != '128' ) {
			return;
		}

		SMPLFY_Log::info( "Approval step completed for entry $entry_id with status: $status" );

		$workCompletedEntry = $this->workCompletedRepository->get_one_by_id( $entry_id );

		$this->update_admin_client_remaining_balance( $status, $workCompletedEntry );
		// TODO: we are probably going to do away with the client balance repository
		$this->update_client_closing_balance( $workCompletedEntry );
	}

	/**
	 * @param $status
	 * @param WorkCompletedEntity $workCompletedEntity
	 *
	 * @return void
	 */
	private function update_admin_client_remaining_balance( $status, WorkCompletedEntity $workCompletedEntity ): void {
		// TODO: why are we not updating the balance adjustments (child entries) here too? (form 151)
		$organizationName = $workCompletedEntity->organisationName;

		SMPLFY_Log::info( "Updating client balances after $status work completed for $organizationName ($workCompletedEntity->clientUserId): ", $workCompletedEntity );

		$adminClientBalance = $this->adminClientBalancesRepository->get_one_by_client_user_id( $workCompletedEntity->clientUserId );

		if ( empty( $adminClientBalance ) ) {
			SMPLFY_Log::error( "Failed to update client remaining balance: No admin client balance found for client user id: $workCompletedEntity->clientUserId" );

			return;
		}

		$hoursBalance   = convert_to_float( $adminClientBalance->currentRealBalance );
		$purchasedHours = convert_to_float( $workCompletedEntity->hoursPurchased );
		$hoursConsumed  = convert_to_float( $workCompletedEntity->hoursSpent );
		$hoursPending   = convert_to_float( $adminClientBalance->balancePendingApproval );

		if ( $status == 'approved' ) {
			if ( $this->is_balance_adjustment_for_hours_purchased( $workCompletedEntity ) ) {
				$hoursNewBalance = $hoursBalance + $purchasedHours;
			} else {
				// TODO: why would there be purchased hours in this case?
				$hoursNewBalance = $hoursBalance - $hoursConsumed + $purchasedHours;
			}

			$adminClientBalance->currentRealBalance = $hoursNewBalance;
			$this->adminClientBalancesRepository->update( $adminClientBalance );

			//TODO: Ask Andre if he would prefer only approved work submissions to be added as child entries to form 150
			SMPLFY_Log::info( "Admin balance: Number of hours remaining updated from $hoursBalance to $hoursNewBalance for $organizationName" );

		} elseif ( $status == 'rejected' ) {
			if ( $this->is_balance_adjustment_for_hours_purchased( $workCompletedEntity ) ) {
				$newPendingBalance = $hoursPending - $purchasedHours;
			} else {
				$newPendingBalance = $hoursPending + $hoursConsumed;
			}

			$adminClientBalance->balancePendingApproval = $newPendingBalance;
			$this->adminClientBalancesRepository->update( $adminClientBalance );

			SMPLFY_Log::info( "Admin balance: Number of hours remaining pending approval updated from $hoursPending to $newPendingBalance for $organizationName" );
		}
	}

	/**
	 * @param WorkCompletedEntity $workCompletedEntry
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


	/**
	 * @param WorkCompletedEntity $workCompletedReport
	 *
	 * @return bool
	 */
	//TODO: This is a repeat of a function in WorkReportSubmitted but I don't know the best place to put it to make it reusable
	public function is_balance_adjustment_for_hours_purchased( WorkCompletedEntity $workCompletedReport ): bool {
		if ( $workCompletedReport->hoursSpent == '' ) {
			return true;
		}

		return false;
	}
}