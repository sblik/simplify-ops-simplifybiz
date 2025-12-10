<?php

/**
 * Adapter for handling Gravity Flow events
 */
class GravityFlowAdapter
{

    private HandleApprovalOnWorkCompleted $handleApprovalOnWorkCompleted;
    private WorkReportApproved            $workReportApproved;

    public function __construct(HandleApprovalOnWorkCompleted $handleApprovalOnWorkCompleted, WorkReportApproved $workReportApproved)
    {
        $this->handleApprovalOnWorkCompleted = $handleApprovalOnWorkCompleted;
        $this->workReportApproved            = $workReportApproved;

        $this->register_hooks();
        $this->register_filters();
    }

    /**
     * Register gravity flow hooks to handle custom logic
     *
     * @return void
     */
    public function register_hooks()
    {
        add_action('gravityflow_step_complete', [$this->handleApprovalOnWorkCompleted, 'update_client_balances'], 10, 4);
        // add_action( 'gravityflow_post_status_update_approval', [ $this->workReportApproved,'handle_workflow_approved'], 10, 4 );
    }

    public function register_filters()
    {

    }
}