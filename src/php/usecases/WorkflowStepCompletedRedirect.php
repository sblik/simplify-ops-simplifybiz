<?php

class WorkflowStepCompletedRedirect
{
    public function __construct()
    {

    }

    /**
     * @param $redirect_url
     * @param $form_id
     * @param $entry_id
     * @return mixed|string
     */
    public function handle_workflow_step_completed($redirect_url, $form_id, $entry_id)
    {
        $currentUser = get_user_by('ID', get_current_user_id());
        $isManager   = UserLogin::does_user_have_role($currentUser, 'manager');

        if (!$isManager) {
            return $redirect_url; // Return default
        }

        switch ($form_id) {
            case 50:
                return "https://ops.simplifybiz.com/inbox/inbox-approvals/";
            case 181:
                return "https://ops.simplifybiz.com/inbox/inbox-internships/";
            case 172:
            case 170:
                return "https://ops.simplifybiz.com/inbox/inbox-task-requests/";
            default:
                return "https://ops.simplifybiz.com/inbox/";
        }
    }
}