<?php

class TaskStatus
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }


    /**
     * @param $form
     * @param $entry_id
     * @param $object
     * @return void
     */
    function handle_stage_change($form, $entry_id, $object = null): void
    {
        if ($form['id'] == FormIDs::TASKS) {
            SMPLFY_Log::info("handle_stage_change triggered: ");
            $taskEntity = $this->taskRepository->get_one_by_id($entry_id);
            SMPLFY_Log::info("Task entity: ", $taskEntity);
            $taskStage = $taskEntity->stage;

            $workflowStepID = $taskEntity->formEntry['workflow_step'];
            SMPLFY_Log::info("Workflow step ID: ", $workflowStepID);
            if ($taskStage == 'To Do' && $workflowStepID !== 238) {
                WorkflowStep::send(238, $taskEntity->formEntry);
            } elseif ($taskStage == 'Doing' && $workflowStepID !== 239) {
                WorkflowStep::send(239, $taskEntity->formEntry);
            } elseif ($taskStage == 'Approve' && $workflowStepID !== 240) {
                WorkflowStep::send(240, $taskEntity->formEntry);
            }
        }
    }

    function handle_step_change_for_task($step_id, $entry_id, $form_id, $status): void
    {
        $pageID = get_the_ID();

        if ($pageID == 19882 && $form_id == 172) {
            wp_redirect(site_url('/inbox/inbox-task-requests/'));
            exit;
        }
    }
    function redirect_after_workflow_cancel( $feedback, $admin_action, $form, $entry ) {
        SMPLFY_Log::info("Admin action triggered: ", $admin_action);

        return $feedback;
    }
}