<?php

/**
 * Handles the admin dashboard page registration and rendering
 */
class AdminDashboard
{
    private BillableHoursReport $billableHoursReport;

    public function __construct(BillableHoursReport $billableHoursReport)
    {
        $this->billableHoursReport = $billableHoursReport;
    }

    /**
     * Render the dashboard page
     */
    public function render_dashboard(): void
    {
        // Get month/year from query params or default to current
        $month = isset($_GET['month']) ? (int) $_GET['month'] : (int) date('n');
        $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

        // Get report data
        $report = $this->billableHoursReport->get_monthly_report($month, $year);

        // Include template
        include BS_NAME_PLUGIN_DIR . 'php/templates/billable-hours-dashboard.php';
    }
}