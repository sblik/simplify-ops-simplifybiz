<?php
/**
 * UseCase for generating billable hours reports
 * Shows daily billable hours and month-to-date totals
 */
class BillableHoursReport
{
    private WorkCompletedRepository $workCompletedRepository;

    public function __construct(WorkCompletedRepository $workCompletedRepository) {
        $this->workCompletedRepository = $workCompletedRepository;
    }

    /**
     * Get entries for a specific date (for debugging)
     */
    public function get_entries_for_date(string $targetDate): array {
        $allWorkReports = $this->workCompletedRepository->get_all();

        $entries = [];
        foreach ($allWorkReports as $r) {
            $transactionDate = $r->transactionDate ?? null;
            // Check if transactionDate matches (handle different date formats)
            if ($transactionDate && str_starts_with($transactionDate, $targetDate)) {
                $entries[] = [
                    'id' => $r->formEntry['id'] ?? 'N/A',
                    'hoursSpent' => $r->hoursSpent,
                    'clientEmail' => $r->clientEmail,
                    'organisationName' => $r->organisationName,
                    'transactionDate' => $transactionDate,
                    'date_created' => $r->formEntry['date_created'] ?? 'N/A',
                    'created_by' => $r->formEntry['created_by'] ?? 'N/A',
                ];
            }
        }

        return $entries;
    }

    /**
     * Debug: Get sample of recent entries to see transactionDate format
     */
    public function get_sample_entries(int $limit = 10): array {
        $allWorkReports = $this->workCompletedRepository->get_all();

        // Get the last X entries (most recent) by reversing the array
        $recentReports = array_slice(array_reverse($allWorkReports), 0, $limit);

        $entries = [];
        foreach ($recentReports as $r) {
            $entries[] = [
                'id' => $r->formEntry['id'] ?? 'N/A',
                'transactionDate_entity' => $r->transactionDate,
                'raw_field_18' => $r->formEntry['18'] ?? 'N/A',
                'hoursSpent' => $r->hoursSpent,
                'date_created' => $r->formEntry['date_created'] ?? 'N/A',
            ];
        }

        return $entries;
    }

    /**
     * Get billable hours grouped by day for a given month
     *
     * @param int $month (1-12)
     * @param int $year
     * @return array ['daily' => [...], 'monthToDate' => float]
     */
    public function get_monthly_report(int $month, int $year): array {
        // Build date range for the month
        $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
        $startDate = new DateTime("{$year}-{$monthPadded}-01");
        $endDate = clone $startDate;
        $endDate->modify('last day of this month');

        // Fetch all work reports
        $allWorkReports = $this->workCompletedRepository->get_all();

        // Filter by date range using transactionDate (field 18)
        $workReports = array_filter($allWorkReports, function($report) use ($startDate, $endDate) {
            $transactionDate = $report->transactionDate ?? null;
            if (empty($transactionDate)) {
                return false;
            }

            if ($report->clientEmail === 'andre@simplifybiz.co') {
                return false;
            }

            try {
                $reportDate = new DateTime($transactionDate);
                return $reportDate >= $startDate && $reportDate <= $endDate;
            } catch (Exception $e) {
                return false;
            }
        });

        // Group by day and calculate totals
        $dailyHours = [];
        $monthToDate = 0.0;

        foreach ($workReports as $report) {
            // Use transactionDate (already in Y-m-d format)
            $date = $report->transactionDate;

            // Parse hours
            $hours = (float) $report->hoursSpent;

            // Initialize day if not exists
            if (!isset($dailyHours[$date])) {
                $dailyHours[$date] = 0.0;
            }

            $dailyHours[$date] += $hours;
            $monthToDate += $hours;
        }

        ksort($dailyHours);

        return [
            'daily' => $dailyHours,
            'monthToDate' => $monthToDate,
            'reportCount' => count($workReports)
        ];
    }

}