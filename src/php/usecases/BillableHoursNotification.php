<?php

/**
 * UseCase for sending daily billable hours report to Google Chat
 */
class BillableHoursNotification
{
    private BillableHoursReport $billableHoursReport;
    private string $webhookUrl;

    public function __construct(BillableHoursReport $billableHoursReport, string $webhookUrl)
    {
        $this->billableHoursReport = $billableHoursReport;

        if ($webhookUrl) {
            $this->webhookUrl = $webhookUrl;
        } elseif (defined('SMPLFY_GOOGLE_CHAT_WEBHOOK_URL')) {
            $this->webhookUrl = SMPLFY_GOOGLE_CHAT_WEBHOOK_URL;
        } else {
            $this->webhookUrl = '';
        }
    }

    /**
     * Send the daily report to Google Chat
     */
    public function send(): bool
    {
        if (empty($this->webhookUrl)) {
            error_log('SendBillableHoursEmail: Google Chat webhook URL not configured');
            return false;
        }

        $today = date('Y-m-d');
        $month = (int)date('m');
        $year = (int)date('Y');

        $monthlyData = $this->billableHoursReport->get_monthly_report($month, $year);
        $todayHours = $monthlyData['daily'][$today] ?? 0;

        $message = $this->buildMessage($today, $todayHours, $monthlyData);

        return $this->sendToGoogleChat($message);
    }

    /**
     * Build the Google Chat message payload
     */
    private function buildMessage(string $today, float $todayHours, array $monthlyData): array
    {
        $monthName = date('F Y');

        return [
            'text' =>
                "*Daily Billable Hours Report*\n\n" .
                "📅 *Date:* {$today}\n" .
                "⏱️ *Today's Hours:* {$todayHours}\n\n" .
                "*Month to Date ({$monthName})*\n" .
                "📊 Total Hours: {$monthlyData['monthToDate']}\n" .
                "📝 Total Entries: {$monthlyData['reportCount']}"
        ];
    }

    /**
     * Send message to Google Chat via webhook
     */
    private function sendToGoogleChat(array $message): bool {
        $response = wp_remote_post($this->webhookUrl, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($message),
            'timeout' => 15,
        ]);

        if (is_wp_error($response)) {
            error_log('SendBillableHoursEmail: ' . $response->get_error_message());
            return false;
        }

        $responseCode = wp_remote_retrieve_response_code($response);
        return $responseCode >= 200 && $responseCode < 300;
    }


}