<?php

class RecalculateClientBalance {
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

	function handle( $entry ) {
		$recalculateClientBalance = new RecalculateClientBalanceEntity( $entry );

		$clientEmail     = $recalculateClientBalance->clientEmail;
		$clientUserID    = get_user_by_email( $clientEmail )->ID;
		$redoWorkReports = $recalculateClientBalance->doesWantToRedoReports;


		$workCompletedReports = $this->workCompletedRepository->get_all( [ '30' => $clientEmail ] );
		$clientBalance        = $this->clientBalancesRepository->get_one_by_client_user_id( $clientUserID );

		if ( empty( $clientBalance ) ) {
			$clientBalance = $this->create_balance_entry( $recalculateClientBalance, $clientUserID );
		}
		if ( $redoWorkReports == 'Yes' ) {
			$this->clientBalancesRepository->delete( $clientBalance->id );
			$clientBalance = $this->create_balance_entry( $recalculateClientBalance, $clientUserID );
			$this->recalculate_reports( $workCompletedReports, $clientBalance, $clientEmail, $clientUserID );
		}
		$this->recalculate_client_balance( $workCompletedReports, $clientBalance );

	}


	function recalculate_client_balance( $workCompletedReports, $clientBalance ) {
		$approvedBalance         = 0;
		$balanceIncludingPending = 0;

		foreach ( $workCompletedReports as $workCompletedReport ) {
			$reportApprovalStatus = $workCompletedReport->formEntry['workflow_step_status_52'];

			$balanceIncludingPending += $workCompletedReport->hoursPurchased;
			$balanceIncludingPending -= $workCompletedReport->hoursSpent;

			if ( $reportApprovalStatus == 'approved' ) {
				$approvedBalance += $workCompletedReport->hoursPurchased;
				$approvedBalance -= $workCompletedReport->hoursSpent;

			}

		}
		$clientBalance->currentBalance         = $approvedBalance;
		$clientBalance->balancePendingApproval = $balanceIncludingPending;

		$this->clientBalancesRepository->update( $clientBalance );
		SMPLFY_Log::info( "CLIENT BALANCE AFTER CALCULATION: ", $clientBalance );
		SMPLFY_Log::info( "BALANCE AFTER CALCULATION: ", $approvedBalance );
		SMPLFY_Log::info( "BALANCE INCLUDING PENDING AFTER CALCULATION: ", $balanceIncludingPending );
	}

	function recalculate_reports( $workCompletedReports, ClientBalanceEntity $clientBalance, $clientEmail, $clientUserID ) {
		foreach ( $workCompletedReports as $workCompletedReport ) {
			$reportApprovalStatus = $workCompletedReport->formEntry['workflow_step_status_52'];
			if ( $reportApprovalStatus == 'approved' ) {

				$adminWorkReportEntries = $this->adminClientBalanceAdjustmentRepository->get_all( [ '1' => $clientEmail ] );

				foreach ( $adminWorkReportEntries as $adminWorkReportEntry ) {
					//TODO: Use repositories after figuring out why the delete method doesn't work with the admin work reports repo
					GFAPI::delete_entry( $adminWorkReportEntry->id );
				}

				$this->add_workreport_to_balance_entity( $workCompletedReport, $clientBalance, $workCompletedReports, $clientUserID );
			}
		}
	}

	/**
	 * @param $workCompletedReport
	 * @param ClientBalanceEntity $clientBalance
	 * @param $clientName
	 * @param $workCompletedReports
	 *
	 * @return void
	 */
	public function add_workreport_to_balance_entity( WorkCompletedEntity $workCompletedReport, ClientBalanceEntity $clientBalance, $workCompletedReports, $clientUserID ): void {
		$clientName = $clientBalance->clientOrganisationName;

//TODO: Move into other class as this code is in 2 places now
		$balanceAdjustment                   = new ClientBalanceAdjustmentEntity();
		$balanceAdjustment->createdBy        = $clientUserID;
		$balanceAdjustment->clientEmail      = $workCompletedReport->clientEmail;
		$balanceAdjustment->clientUserId     = $workCompletedReport->clientUserId;
		$balanceAdjustment->clientFirstName  = $workCompletedReport->clientFirstName;
		$balanceAdjustment->clientLastName   = $workCompletedReport->clientLastName;
		$balanceAdjustment->organisationName = $workCompletedReport->organisationName;
		$balanceAdjustment->transactionDate  = $workCompletedReport->transactionDate;
		$balanceAdjustment->requestSummary   = $workCompletedReport->requestSummary;
		$balanceAdjustment->project          = $workCompletedReport->project;
		$balanceAdjustment->workCompleted    = $workCompletedReport->workCompleted;
		$balanceAdjustment->hoursSpent       = $workCompletedReport->hoursSpent;
		$balanceAdjustment->parentKey        = $clientBalance->id;

		$addResult = $this->adminClientBalanceAdjustmentRepository->add( $balanceAdjustment );

		if ( $addResult instanceof WP_Error ) {
			SMPLFY_Log::error( "Failed to add balance adjustment for $clientName.", [
				'errors'              => $addResult->errors,
				'work_complete_entry' => $workCompletedReports,
			] );
		}
	}

	function get_gform_entry( $formID, $fieldValue, $fieldId ) {

		$retrieved_entry_form_id = $formID;

		// Search Form with criteria to get entry
		$retrieved_entry_search_criteria['field_filters'][] = array( 'key' => $fieldId, 'value' => $fieldValue );
		$retrieved_entry_search_criteria['status']          = 'active';
		$retrieved_entry_sorting                            = array(
			'key'        => 'id',
			'direction'  => 'ASC',
			'is_numeric' => true,
		);
		$retrieved_entry_entries                            = GFAPI::get_entries( $retrieved_entry_form_id, $retrieved_entry_search_criteria, $retrieved_entry_sorting );

		if ( ! empty( $retrieved_entry_entries ) ) {
			return $retrieved_entry_entries[0];
		}

		return null;
	}

	/**
	 * @param RecalculateClientBalanceEntity $recalculateClientBalance
	 * @param int $clientUserID
	 *
	 * @return ClientBalanceEntity|null
	 */
	public function create_balance_entry( RecalculateClientBalanceEntity $recalculateClientBalance, int $clientUserID ): ?ClientBalanceEntity {
//TODO: Move into class as two instances of adding a client balance entity like this exist
		$clientAdminBalance                         = new ClientBalanceEntity();
		$clientAdminBalance->clientEmail            = $recalculateClientBalance->clientEmail;
		$clientAdminBalance->clientUserId           = $clientUserID;
		$clientAdminBalance->balancePendingApproval = 0;
		$clientAdminBalance->currentBalance         = 0;
		$clientAdminBalance->clientOrganisationName = $recalculateClientBalance->organisationName;
		$this->clientBalancesRepository->add( $clientAdminBalance );

		return $this->clientBalancesRepository->get_one_by_client_user_id( $clientUserID );
	}
}