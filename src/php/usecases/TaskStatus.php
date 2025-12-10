<?php

class TaskStatus
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }


    function handle_stage_change($step_id, $entry_id, $form_id, $status): void
    {
        if ($form_id == FormIDs::TASKS) {
            $taskEntity = $this->taskRepository->get_one_by_id($entry_id);

            $taskStage = $taskEntity->stage;

            if ($taskStage == 'To Do' && $step_id !== 238) {
                WorkflowStep::send(238, $taskEntity->formEntry);
            } elseif ($taskStage == 'Doing' && $step_id !== 239) {
                WorkflowStep::send(239, $taskEntity->formEntry);
            } elseif ($taskStage == 'Approve' && $step_id !== 240) {
                WorkflowStep::send(240, $taskEntity->formEntry);
            }
        }
    }
}