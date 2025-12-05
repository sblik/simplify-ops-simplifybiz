<?php

/**
 * Handles the admin dashboard page registration and rendering
 */
class AdminDashboard
{
    private BillableHoursReport $billableHoursReport;
    private BillableHoursNotification $billableHoursNotification;

    public function __construct(BillableHoursReport $billableHoursReport, BillableHoursNotification $billableHoursNotification)
    {
        $this->billableHoursReport = $billableHoursReport;
        $this->billableHoursNotification = $billableHoursNotification;
    }

    /**
     * Render the dashboard page
     */
    public function render_dashboard(): void
    {
        // Handle send test email action
        $emailSent = null;
        if (isset($_POST['send_test_email']) && check_admin_referer('send_billable_hours_email')) {
            $emailSent = $this->billableHoursNotification->send();
        }
        // Get month/year from query params or default to current
        $month = isset($_GET['month']) ? (int) $_GET['month'] : (int) date('n');
        $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

        // DEBUG: Show sample entries to see transactionDate format
//        $sampleEntries = $this->billableHoursReport->get_sample_entries(15);
//        echo '<div class="wrap">';
//        echo '<h2>Sample entries with transactionDate (' . count($sampleEntries) . ' entries)</h2>';
//        echo '<table class="wp-list-table widefat fixed striped">';
//        echo '<thead><tr><th>ID</th><th>Entity transactionDate</th><th>Raw field 18</th><th>Hours</th><th>Date Created</th></tr></thead>';
//        echo '<tbody>';
//        foreach ($sampleEntries as $entry) {
//            echo '<tr>';
//            echo '<td>' . esc_html($entry['id']) . '</td>';
//            echo '<td>' . esc_html($entry['transactionDate_entity'] ?? '') . '</td>';
//            echo '<td>' . esc_html($entry['raw_field_18'] ?? '') . '</td>';
//            echo '<td>' . esc_html($entry['hoursSpent']) . '</td>';
//            echo '<td>' . esc_html($entry['date_created']) . '</td>';
//            echo '</tr>';
//        }
//        echo '</tbody></table>';
//        echo '</div>';

        // Get report data
        $report = $this->billableHoursReport->get_monthly_report($month, $year);

        // Include template
        include BS_NAME_PLUGIN_DIR . 'php/templates/billable-hours-dashboard.php';
    }
}