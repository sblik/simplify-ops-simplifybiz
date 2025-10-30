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
        SMPLFY_Log::info("handle_workflow_approved triggered: ", $new_status);
        if ( $new_status == 'approved' ) {
            SMPLFY_Log::info("IN if new status is approved");
            wp_redirect("https://ops.simplifybiz.com/inbox");
            exit;
        }
    }


}