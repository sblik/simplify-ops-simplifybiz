<?php

/**
 *
 * @property $repEmail
 * @property $organisation
 * @property $primaryEmail
 * @property $selectProject
 * @property $project
 * @property $task
 * @property $taskDescription
 * @property $priority
 * @property $dateDue
 * @property $dateCompleted
 * @property $taskStatusClient
 * @property $initialReviewStatus
 * @property $stage
 * @property $taskStatusManagement
 * @property $notesRepeater
 * @property $feedback
 * @property $clientSearch
 * @property $billClient
 * @property $task2
 * @property $taskDescription2
 * @property $milestones
 * @property $priority2
 * @property $nextAction
 * @property $category
 * @property $assignee
 * @property $assignedEmployee
 * @property $dueDate
 * @property $dueTime
 * @property $estimateHours
 * @property $actualHours
 * @property $internalNotesRepeater
 * @property $internalLinksRepeater
 */

class TaskEntity extends SMPLFY_BaseEntity
{
    public function __construct($formEntry = array())
    {
        parent::__construct($formEntry);
        $this->formId = FormIDs::TASKS;
    }

    protected function get_property_map(): array
    {
        return array(
            'repEmail'              => '33',
            'organisation'          => '5',
            'primaryEmail'          => '40',
            'selectProject'         => '38',
            'project'               => '25',
            'task'                  => '21',
            'taskDescription'       => '26',
            'priority'              => '23',
            'dateDue'               => '14',
            'dateCompleted'         => '43',
            'taskStatusClient'      => '41',
            'initialReviewStatus'   => '66',
            'stage'                 => '70',
            'taskStatusManagement'   => '71',
            'notesRepeater'         => '75',
            'feedback'              => '42',
            'clientSearch'          => '46',
            'billClient'            => '47',
            'task2'                 => '53',
            'taskDescription2'      => '58',
            'milestones'            => '59',
            'priority2'             => '60',
            'nextAction'            => '61',
            'category'              => '62',
            'assignee'              => '74',
            'assignedEmployee'      => '78',
            'dueDate'               => '64',
            'dueTime'               => '69',
            'estimateHours'         => '65',
            'actualHours'           => '73',
            'internalNotesRepeater' => '72',
            'internalLinksRepeater' => '79',
        );
    }
}