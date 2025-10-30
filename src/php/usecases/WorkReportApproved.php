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

        $formID = $form['id'];
        SMPLFY_Log::info("Form ID: ", $formID);
            if ( $new_status == 'approved' ) {
                SMPLFY_Log::info("IN if new status is approved");
                if($formID == 50){
                    $redirectURL = "https://ops.simplifybiz.com/inbox/inbox-approvals/";
                } elseif($formID == 181){
                    $redirectURL = "https://ops.simplifybiz.com/inbox/inbox-internships/";
                }
                elseif($formID == 172){
                   $redirectURL = "https://ops.simplifybiz.com/inbox/inbox-task-requests/";
                }
                if(!empty($redirectURL)){
                    SMPLFY_Log::info("Redirect URL: ", $redirectURL);
                    wp_redirect("https://ops.simplifybiz.com/inbox/inbox-approvals/");
                }

               die();
            }
    }


}