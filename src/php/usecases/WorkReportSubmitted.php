<?php

class WorkReportSubmitted {
	private WorkCompletedRepository $workCompletedReportsRepository;
	private ClientBalanceRepository $adminClientBalanceRepository;

	public function __construct( WorkCompletedRepository $workCompletedReportsRepository, ClientBalanceRepository $adminClientBalanceRepository ) {
		$this->workCompletedReportsRepository = $workCompletedReportsRepository;
		$this->adminClientBalanceRepository   = $adminClientBalanceRepository;
	}

	function handle( $entry ) {
		$workCompletedReport = new WorkCompletedEntity( $entry );

		$clientEmailInReport = $workCompletedReport->clientEmail;
		$clientUserId        = get_user_by_email( $clientEmailInReport )->ID;

		$this->store_client_user_id( $workCompletedReport, $clientUserId );

		$clientAdminBalance = $this->adminClientBalanceRepository->get_one( [ ClientBalanceEntity::get_field_id( 'clientUserId' ) => $clientUserId ] );

		if ( $clientEmailInReport !== $clientAdminBalance->clientEmail ) {
			$clientAdminBalance->clientEmail = $clientEmailInReport;
			// TODO: there is potentially a double update here, because  of the subsequent pending balance update
			// TODO: we ideally only want to see one update
			$this->adminClientBalanceRepository->update( $clientAdminBalance );
		}

		$this->update_pending_balance( $clientAdminBalance, $workCompletedReport );
	}

	/**
	 * @param WorkCompletedEntity $workCompletedReport
	 * @param $clientUserId
	 *
	 */
	public function store_client_user_id( WorkCompletedEntity $workCompletedReport, $clientUserId ) {
		$workCompletedReport->clientUserId = $clientUserId;
		$this->workCompletedReportsRepository->update( $workCompletedReport );
	}

	/**
	 * @param WorkCompletedEntity $workCompletedReport
	 *
	 * @return bool
	 */
	public function is_balance_adjustment_for_hours_purchased( WorkCompletedEntity $workCompletedReport ): bool {
		if ( $workCompletedReport->hoursSpent == '' ) {
			return true;
		}

		return false;
	}

	/**
	 * @param ClientBalanceEntity|null $clientAdminBalance
	 * @param WorkCompletedEntity $workCompletedReport
	 *
	 * @return void
	 */
	public function update_pending_balance( ?ClientBalanceEntity $clientAdminBalance, WorkCompletedEntity $workCompletedReport ): void {
		SMPLFY_Log::info( "IN UPDATE PENDING BALANCE --" );
		SMPLFY_Log::info( "CLIENT ADMIN BALANCE ENTITY: ", $clientAdminBalance );
		SMPLFY_Log::info( "WORK COMPLETED REPORT ENTITY: ", $workCompletedReport );
		$currentPendingHoursForClient = $clientAdminBalance->balancePendingApproval;
		if ( $this->is_balance_adjustment_for_hours_purchased( $workCompletedReport ) ) {
			$newPendingAmount = $currentPendingHoursForClient + $workCompletedReport->hoursPurchased;
		} else {
			$newPendingAmount = $currentPendingHoursForClient - $workCompletedReport->hoursSpent;
		}
		$clientAdminBalance->balancePendingApproval = $newPendingAmount;
		$this->adminClientBalanceRepository->update( $clientAdminBalance );
	}
}