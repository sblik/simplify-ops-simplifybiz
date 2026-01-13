<?php

/**
 * Adapter for handling Gravity Forms events
 */
class GravityFormsAdapter
{

    private UpdateHoursWorked        $updateHoursWorked;
    private WorkReportSubmitted      $workReportSubmitted;
    private RecalculateClientBalance $recalculateClientBalance;
    private TaskStatus               $taskStatus;

    public function __construct(UpdateHoursWorked $updateHoursWorked, WorkReportSubmitted $workReportSubmitted, RecalculateClientBalance $recalculateClientBalance, TaskStatus $taskStatus)
    {
        $this->updateHoursWorked        = $updateHoursWorked;
        $this->workReportSubmitted      = $workReportSubmitted;
        $this->recalculateClientBalance = $recalculateClientBalance;
        $this->taskStatus               = $taskStatus;

        $this->register_hooks();
        $this->register_filters();
    }

    /**
     * Register gravity forms hooks to handle custom logic
     *
     * @return void
     */
    public function register_hooks()
    {

        add_action('gform_after_submission_161', [$this->updateHoursWorked, 'update_dev_rate'], 10, 2);
        add_action('gform_after_submission_50', [$this->workReportSubmitted, 'handle'], 10, 2);
        add_action('gform_after_submission_163', [$this->recalculateClientBalance, 'handle'], 10, 2);
    }

    public function register_filters()
    {
        add_filter('gform_pre_render_172', [$this->taskStatus, 'generate_do_items_checklist']);
    }
}