<?php

class WorkflowStepCompletedRedirect
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
    public function handle_workflow_step_completed( $step_id, $entry_id, $formID, $status )
    {
        SMPLFY_Log::info("handle_workflow_step_completed triggered: ", $status);

        SMPLFY_Log::info("Form ID: ", $formID);
        $currentUser = get_user_by('ID', get_current_user_id());
        $isManager   = UserActions::does_user_have_role($currentUser, 'manager');
        if ($isManager) {
            if ($formID == 50) {
                $redirectURL = "https://ops.simplifybiz.com/inbox/inbox-approvals/";
            } elseif ($formID == 181) {
                $redirectURL = "https://ops.simplifybiz.com/inbox/inbox-internships/";
            } elseif ($formID == 172 || $formID == 170) {
                $redirectURL = "https://ops.simplifybiz.com/inbox/inbox-task-requests/";
            }
            if (!empty($redirectURL)) {
                SMPLFY_Log::info("Redirect URL: ", $redirectURL);
                wp_redirect("https://ops.simplifybiz.com/inbox/inbox-approvals/");
            } else {
                wp_redirect("https://ops.simplifybiz.com/inbox/");
            }

            exit;
        }
    }
}