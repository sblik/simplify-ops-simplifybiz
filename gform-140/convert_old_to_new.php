<?php

/*
 * Form ID 50 'OPS: Customer Balance Management'
 * update all entries where
 * - client user id is common
 * - new purchased minutes is empty && new consumed minutes is empty
 *
 * */

$cuid = rgar($entry, 3); // Client User ID

// Find all entries in Form ID 50 where CUID is common. Sort oldest to newest by entry id

// Find Entry

$bs_form_id = 50; //
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

$bs_entries = GFAPI::get_entries($bs_form_id, $bs_search_criteria, $bs_sorting, $bs_paging, $bs_total_count);
// bs('Entries');
// bs($bs_entries);
for ($x = 0; $x < $bs_total_count; $x++) {
    $bs_entry = $bs_entries[$x];

    $bs_entry_id = $bs_entry['id'];
    bs('Form ID 50 Entry Id: ' . $bs_entry_id);

    $minutes_purchased_old = $bs_entry[10];
    $minutes_purchased_old = intval(str_replace(',', '', $minutes_purchased_old));
    bs('Minutes Purchased Old: ' . $minutes_purchased_old);
    $minutes_consumed_old = $bs_entry[11];
    $minutes_consumed_old = intval(str_replace(',', '', $minutes_consumed_old));
    bs('Minutes Consumed Old: ' . $minutes_consumed_old);

    // Check if old fields are populated
    if (
        $minutes_purchased_old > 0 ||
        $minutes_consumed_old > 0
    ) {

        bs('$minutes_purchased_old > 0 ||$minutes_consumed_old > 0');
        bs('Minutes Purchased Old: ' . $minutes_purchased_old);
        bs('Minutes Consumed Old: ' . $minutes_consumed_old);
        // if old fields are populated, populate new fields
        $result = GFAPI::update_entry_field($bs_entry_id, 67, $minutes_purchased_old);
        $result = GFAPI::update_entry_field($bs_entry_id, 66, $minutes_consumed_old);

    }

}
