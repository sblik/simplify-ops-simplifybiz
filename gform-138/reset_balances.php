<?php

// GET ENTRIES
bs('/gform-138/reset_balances.php');

$cuid = rgar($entry, 3); // Client User ID
$entry_id = rgar($entry, 'id');

$bs_form_id = 50;
$bs_search_criteria = array(
    'status' => 'active',
    'field_filters' => array(
        'mode' => 'any',
        array(
            'key' => '2', // Clients user ID
            'value' => $cuid,
        ),
    ),
);

$bs_sort_field = 'id';
$bs_sorting = array('key' => $bs_sort_field, 'direction' => 'ASC', 'is_numeric' => true);
$bs_paging = array('offset' => 0, 'page_size' => 1000);
$bs_total_count = 0;

$bs_entries = GFAPI::get_entries($bs_form_id, $bs_search_criteria, $bs_sorting, $bs_paging, $bs_total_count);

$hours_balance = floatval($entry[6]);
$minutes_balance = intval($entry[7]);



// bs('Number of Entries: ' . $bs_entries_number);

// For Each Entry
for ($x = 0; $x < $bs_total_count; $x++) {
    $bs_entry = $bs_entries[$x];
    $bs_entry_id = $bs_entry['id'];
    $bs_date_created = $bs_entry['date_created'];
    $bs_timestamp_date_created = strtotime($bs_date_created);
    $bs_date_updated = $bs_entry['date_updated'];
    $bs_timestamp_date_updated = strtotime($bs_date_updated);

    // MINUTES
    $minutes_purchased = intval($bs_entry[67]);
    $minutes_consumed = intval($bs_entry[66]);
    $minutes_balance_close = intval($minutes_balance + $minutes_purchased - $minutes_consumed);

    $result = GFAPI::update_entry_field($bs_entry_id, 16, $minutes_balance); // Balance F/Fwd
    $result = GFAPI::update_entry_field($bs_entry_id, 12, $minutes_balance_close); // Balance

    // HOURS
    $hours_purchased = floatval(round(($minutes_purchased / 60), 2));
    if ($hours_purchased == 0) {
        $hours_purchased = '';
    }
    $hours_consumed = floatval(round(($minutes_consumed / 60), 2));
    if ($hours_consumed == 0) {
        $hours_consumed = '';
    }

    $hours_balance_close = floatval(round(($hours_balance + $hours_purchased - $hours_consumed), 2));

    // Update Hours Open, Purchased, Consumed, Close
    $result = GFAPI::update_entry_field($bs_entry_id, 56, $hours_balance); // Balance F/Fwd
    $result = GFAPI::update_entry_field($bs_entry_id, 68, $hours_purchased); // Balance
    $result = GFAPI::update_entry_field($bs_entry_id, 46, $hours_consumed); // Balance F/Fwd
    $result = GFAPI::update_entry_field($bs_entry_id, 57, $hours_balance_close); // Balance Closing
    $result = GFAPI::update_entry_field($bs_entry_id, 72, $bs_timestamp_date_created); // Timestamp Date Created
    $result = GFAPI::update_entry_field($bs_entry_id, 73, $bs_timestamp_date_updated); // Timestamp Date Updated

    // Update Hours and Minutes Balance for next entry
    $minutes_balance = $minutes_balance_close;
    $hours_balance = $hours_balance_close;
}

$result = GFAPI::update_entry_field($entry_id, 6, $hours_balance); // Balance Closing
$result = GFAPI::update_entry_field($entry_id, 7, $minutes_balance); // Balance Closing
