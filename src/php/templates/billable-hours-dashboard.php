<?php
/**
 * Template for Billable Hours Dashboard
 * Variables available: $month, $year, $report
 */
?>
<div class="wrap">
    <h1>Billable Hours Report</h1>

    <!--  Month/Year Selector  -->
    <form method="get" style="margin-bottom: 20px;">
        <input type="hidden" name="page" value="billable-hours-dashboard">
        <select name="month">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?php echo $m; ?>" <?php selected($m, $month); ?>>
                    <?php echo date("F", mktime(0,0,0,$m, 1)); ?>
                </option>
            <?php endfor; ?>
        </select>
        <select name="year">
            <?php for ($y = date("Y") - 2; $y <= date("Y"); $y++): ?>
                <option value="<?php echo $y; ?>" <?php selected($y, $year); ?>>
                    <?php echo $y; ?>
                </option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="button">View Report</button>
    </form>
    <!-- Summary Card -->
    <div class="card" style="max-width: 300px; padding: 15px; margin-bottom: 20px;">
        <h2 style="margin-top: 0;">Month to Date</h2>
        <p style="font-size: 32px; font-weight: bold; margin: 0;">
            <?php echo number_format($report['monthToDate'], 2); ?> hours
        </p>
        <p style="color: #666;">
            From <?php echo $report['reportCount']; ?> work reports
        </p>
    </div>

    <!-- Daily Breakdown Table -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>Billable Hours</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($report['daily'])): ?>
            <tr>
                <td colspan="2">No work reports found for this period.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($report['daily'] as $date => $hours): ?>
                <tr>
                    <td><?php echo esc_html($date); ?></td>
                    <td><?php echo number_format($hours, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
