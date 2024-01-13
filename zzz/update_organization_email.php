<?php

/*
 * Upate email address associated with the organization in
 *
 * - Form ID 53: 'Add a Client' One Entry
 * - Form ID 50: 'OPS: Customer Balance Management' All Entries
 *
 * */

/* ****************************************************************
 * Get Variables from $entry
 * **************************************************************** */

$cuid = rgar($entry, 3); // Client User ID
$email_new = rgar($entry, 7);

// Find all entries in Form ID 50 where CUID is common. Sort oldest to newest by entry id
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
// BS_Log::info('Entries');
// BS_Log::info($bs_entries);
for ($x = 0; $x < $bs_total_count; $x++) {
    $bs_entry = $bs_entries[$x];

    $bs_entry_id = $bs_entry['id'];
    BS_Log::info('Form ID 50 Entry Id: ' . $bs_entry_id);

    $email_dropdown_old = $bs_entry[30];
    BS_Log::info('Email Dropdown Old: ' . $email_dropdown_old);
    $email_old = $bs_entry[31];
    BS_Log::info('Email  Old: ' . $email_old);
    BS_Log::info('Email New: ' . $email_new);

    // if old fields are populated, populate new fields
    $result = GFAPI::update_entry_field($bs_entry_id, 30, $email_new);
    $result = GFAPI::update_entry_field($bs_entry_id, 31, $email_new);

}
