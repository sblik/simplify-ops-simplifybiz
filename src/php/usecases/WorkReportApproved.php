<?php

class WorkReportApproved
{
    public function __construct()
    {

    }

    /**
     * Entry method for the use case to handle a user registration.
     *
     * @param $userId
     * @param $feed
     * @param $entry
     *
     * @return void
     */
    public function handle_workflow_approved($entry, $assignee, $new_status, $form)
    {
        BS_Log::info("handle_workflow_approved triggered: ", $new_status);
            if ( $new_status == 'approved' ) {
                BS_Log::info("IN if new status is approved");
               wp_redirect("https://ops.simplifybiz.com/manager-dashboard/manager-tools/approvals/");
               die();
            }
    }


}