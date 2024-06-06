<?php

/*
 * When a work completed entry (form 50) is approved or rejected, we need to update the client balances accordingly.
 */

class HandleApprovalOnWorkCompleted {
	private WorkCompletedRepository $workCompletedRepository;
	private ClientBalanceRepository $clientBalancesRepository;
	private ClientBalanceAdjustmentRepository $adminClientBalanceAdjustmentRepository;

	public function __construct(
		WorkCompletedRepository $workCompletedRepository,
		ClientBalanceRepository $clientBalancesRepository,
		ClientBalanceAdjustmentRepository $adminClientBalanceAdjustmentRepository
	) {
		$this->workCompletedRepository                = $workCompletedRepository;
		$this->clientBalancesRepository               = $clientBalancesRepository;
		$this->adminClientBalanceAdjustmentRepository = $adminClientBalanceAdjustmentRepository;
	}

	function update_client_balances( $step_id, $entry_id, $form_id, $status ): void {
		if ( $form_id == 50 ) {
			if ( $step_id != '52' ) {
				return;
			}

			SMPLFY_Log::info( "Approval step completed for entry $entry_id with status: $status" );

			$workCompletedEntity = $this->workCompletedRepository->get_one_by_id( $entry_id );

			$this->update_admin_client_remaining_balance( $status, $workCompletedEntity );
		}
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

		$adminClientBalance = $this->clientBalancesRepository->get_one_by_client_user_id( $workCompletedEntity->clientUserId );

		if ( empty( $adminClientBalance ) ) {
			SMPLFY_Log::error( "Failed to update client remaining balance: No admin client balance found for client user id: $workCompletedEntity->clientUserId" );

			return;
		}

		$hoursBalance   = convert_to_float( $adminClientBalance->currentBalance );
		$hoursPurchased = convert_to_float( $workCompletedEntity->hoursPurchased );
		$hoursConsumed  = convert_to_float( $workCompletedEntity->hoursSpent );
		$hoursPending   = convert_to_float( $adminClientBalance->balancePendingApproval );

		SMPLFY_Log::info( 'Balances prior to update: ', array(
			'Hours Balance'   => $hoursBalance,
			'Hours Purchased' => $hoursPurchased,
			'Hours Consumed'  => $hoursConsumed,
		) );

		if ( $status == 'approved' ) {
			if ( $this->is_balance_adjustment_for_hours_purchased( $workCompletedEntity ) ) {
				$hoursNewBalance = $hoursBalance + $hoursPurchased;
			} else {
				// TODO: why would there be purchased hours in this case?
				$hoursNewBalance = $hoursBalance - $hoursConsumed + $hoursPurchased;
			}

			$adminClientBalance->currentBalance = $hoursNewBalance;
			$this->clientBalancesRepository->update( $adminClientBalance );

			$this->create_balance_adjustments_for_client( $adminClientBalance->clientEmail, $workCompletedEntity, $adminClientBalance );

			//TODO: Ask Andre if he would prefer only approved work submissions to be added as child entries to form 150
			SMPLFY_Log::info( "Client balance: Number of hours remaining updated from $hoursBalance to $hoursNewBalance for $organizationName" );

		} elseif ( $status == 'rejected' ) {
			if ( $this->is_balance_adjustment_for_hours_purchased( $workCompletedEntity ) ) {
				$newPendingBalance = $hoursPending - $hoursPurchased;
			} else {
				$newPendingBalance = $hoursPending + $hoursConsumed;
			}

			$adminClientBalance->balancePendingApproval = $newPendingBalance;
			$this->clientBalancesRepository->update( $adminClientBalance );

			SMPLFY_Log::info( "Client balance: Number of hours remaining pending approval updated from $hoursPending to $newPendingBalance for $organizationName" );
		}
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

	/**
	 * @param string $clientName
	 * @param int $clientUserId
	 *
	 * @return void
	 */
	public function create_balance_adjustments_for_client( string $clientName, WorkCompletedEntity $workCompletedEntity, ClientBalanceEntity $clientBalances ): void {
		SMPLFY_Log::info( "Updating client balances for $clientName" );

		$balanceAdjustment                   = new ClientBalanceAdjustmentEntity();
		$balanceAdjustment->clientEmail      = $workCompletedEntity->clientEmail;
		$balanceAdjustment->clientUserId     = $workCompletedEntity->clientUserId;
		$balanceAdjustment->clientFirstName  = $workCompletedEntity->clientFirstName;
		$balanceAdjustment->clientLastName   = $workCompletedEntity->clientLastName;
		$balanceAdjustment->organisationName = $workCompletedEntity->organisationName;
		$balanceAdjustment->transactionDate  = $workCompletedEntity->transactionDate;
		$balanceAdjustment->requestSummary   = $workCompletedEntity->requestSummary;
		$balanceAdjustment->workCompleted    = $workCompletedEntity->workCompleted;
		$balanceAdjustment->hoursSpent       = $workCompletedEntity->hoursSpent;
		$balanceAdjustment->parentKey        = $clientBalances->id;

		$addResult = $this->adminClientBalanceAdjustmentRepository->add( $balanceAdjustment );

		if ( $addResult instanceof WP_Error ) {
			SMPLFY_Log::error( "Failed to add balance adjustment for $clientName.", [
				'errors'              => $addResult->errors,
				'work_complete_entry' => $workCompletedEntity,
			] );
		}


		SMPLFY_Log::info( "Finished processing balance adjustments for $clientName" );
	}
}