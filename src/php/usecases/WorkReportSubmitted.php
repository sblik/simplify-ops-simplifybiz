<?php

class WorkReportSubmitted {
	private WorkCompletedRepository $workCompletedReportsRepository;
	private AdminClientBalanceRepository $adminClientBalanceRepository;

	public function __construct( WorkCompletedRepository $workCompletedReportsRepository, AdminClientBalanceRepository $adminClientBalanceRepository ) {
		$this->workCompletedReportsRepository = $workCompletedReportsRepository;
		$this->adminClientBalanceRepository   = $adminClientBalanceRepository;
	}

	function handle( $entry ) {
		$workCompletedReport = new WorkCompletedEntity( $entry );

		$clientEmailInReport = $workCompletedReport->clientEmail;
		$clientUserId        = get_user_by_email( $clientEmailInReport )->ID;

		$this->store_client_user_id( $workCompletedReport, $clientUserId );

		$clientAdminBalance = $this->adminClientBalanceRepository->get_one( [ AdminClientBalanceEntity::get_field_id( 'clientUserId' ) => $clientUserId ] );

		if ( $clientEmailInReport !== $clientAdminBalance->clientEmail ) {
			$clientAdminBalance->clientEmail = $clientEmailInReport;
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
	public function is_report_balance_adjustment( WorkCompletedEntity $workCompletedReport ): bool {
		if ( $workCompletedReport->hoursSpent == '' ) {
			return true;
		}

		return false;
	}

	/**
	 * @param AdminClientBalanceEntity|null $clientAdminBalance
	 * @param WorkCompletedEntity $workCompletedReport
	 *
	 * @return void
	 */
	public function update_pending_balance( ?AdminClientBalanceEntity $clientAdminBalance, WorkCompletedEntity $workCompletedReport ): void {
		$currentPendingHoursForClient = $clientAdminBalance->balancePendingApproval;
		if ( $this->is_report_balance_adjustment( $workCompletedReport ) ) {
			$newPendingAmount = $currentPendingHoursForClient + $workCompletedReport->hoursPurchased;
		} else {
			$newPendingAmount = $currentPendingHoursForClient - $workCompletedReport->hoursSpent;
		}
		$clientAdminBalance->balancePendingApproval = $newPendingAmount;
		$this->adminClientBalanceRepository->update( $clientAdminBalance );
	}
}