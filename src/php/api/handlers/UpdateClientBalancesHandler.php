<?php

class UpdateClientBalancesHandler {
	private WorkCompletedRepository $workCompletedRepository;
	private AdminClientBalanceRepository $clientBalancesRepository;
	private AdminClientBalanceAdjustmentRepository $clientBalanceAdjustmentRepository;

	public function __construct(
		WorkCompletedRepository $workCompletedRepository,
		AdminClientBalanceRepository $clientBalancesRepository,
		AdminClientBalanceAdjustmentRepository $clientBalanceAdjustmentRepository
	) {
		$this->workCompletedRepository           = $workCompletedRepository;
		$this->clientBalancesRepository          = $clientBalancesRepository;
		$this->clientBalanceAdjustmentRepository = $clientBalanceAdjustmentRepository;
	}

	function handle() {
		$clientUserIds = [
			'Divorce Concierge'     => 23,
			'Plug And Play SM'      => 27,
			'The Ramage Law Group ' => 64,
			'RBCA'                  => 88,
			'Bliksem LLC'           => 104,
			'AmpianHR'              => 105,
			'Municipal Solutions'   => 53,
		];

		foreach ( $clientUserIds as $clientName => $clientUserId ) {
			$this->create_balance_adjustments_for_client( $clientName, $clientUserId );
		}
	}

	/**
	 * @param  string  $clientName
	 * @param  int  $clientUserId
	 *
	 * @return void
	 */
	public function create_balance_adjustments_for_client( string $clientName, int $clientUserId ): void {
		SMPLFY_Log::info( "Updating client balances for $clientName" );

		$workCompletedEntries = $this->workCompletedRepository->get_all();
		$workEntryCount       = count( $workCompletedEntries );

		SMPLFY_Log::info( "Found $workEntryCount work completed entries for $clientName" );

		$clientBalances = $this->clientBalancesRepository->get_one_by_client_user_id( $clientUserId );

		foreach ( $workCompletedEntries as $workCompletedEntry ) {
			$balanceAdjustment                   = new AdminClientBalanceAdjustmentEntity();
			$balanceAdjustment->clientEmail      = $workCompletedEntry->clientEmail;
			$balanceAdjustment->clientUserId     = $workCompletedEntry->clientUserId;
			$balanceAdjustment->clientFirstName  = $workCompletedEntry->clientFirstName;
			$balanceAdjustment->clientLastName   = $workCompletedEntry->clientLastName;
			$balanceAdjustment->organisationName = $workCompletedEntry->organisationName;
			$balanceAdjustment->transactionDate  = $workCompletedEntry->transactionDate;
			$balanceAdjustment->requestSummary   = $workCompletedEntry->requestSummary;
			$balanceAdjustment->workCompleted    = $workCompletedEntry->workCompleted;
			$balanceAdjustment->hoursSpent       = $workCompletedEntry->hoursSpent;
			$balanceAdjustment->parentKey        = $clientBalances->id;

			$addResult = $this->clientBalanceAdjustmentRepository->add( $balanceAdjustment );

			if ( $addResult instanceof WP_Error ) {
				SMPLFY_Log::error( "Failed to add balance adjustment for $clientName.", [
					'errors'              => $addResult->errors,
					'work_complete_entry' => $workCompletedEntry,
				] );
			}
		}

		SMPLFY_Log::info( "Finished processing balance adjustments for $clientName" );
	}
}