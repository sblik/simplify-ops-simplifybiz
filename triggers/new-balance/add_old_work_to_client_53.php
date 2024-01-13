
<?php

 function bs_create_child_submissions_150_53(){
BS_Log::info("FUNCTION 53 TRIGGERED");
$ops_form_id = 50;
$search_criteria['field_filters'][] = array('key' => null, 'value' => null);
$search_criteria['status'][] = 'active';
$paging          = array( 'offset' => 0, 'page_size' => 2000);
$sorting = array('key' => 'id', 'direction' => 'DESC', 'is_numeric' => true);
$ops_entries = GFAPI::get_entries($ops_form_id, $search_criteria, $sorting, $paging);

$count_ops_entries = count($ops_entries);

BS_Log::info("COUNT OPS ENTRIES: " . $count_ops_entries);
/***
 *  CLIENT 53
 */
$clients_form_id = 150;
$search_criteria['field_filters'][] = array('key' => '3', 'value' => 53);
$search_criteria['status'][] = 'active';
$sorting = array('key' => 'id', 'direction' => 'DESC', 'is_numeric' => true);
$client_entries_1 = GFAPI::get_entries($clients_form_id, $search_criteria, $sorting);
BS_Log::info($client_entries_1[0]);

for ($i = 0; $i < $count_ops_entries; $i++) {
    BS_Log::info("CLIENT USER ID: " . $ops_entries[$i][2]);
    
    if($ops_entries[$i][2] == 53){
    $client_entries_1[0];
    BS_Log::info("CLIENT ENTRY USER ID: " . $client_entries_1[0][2]);

    
    
        $clientsEmail = $ops_entries[$i]['30'];
        $requestSummary = $ops_entries[$i]['39'];
        $clientUserID = $ops_entries[$i]['2'];
        $clientFirstName = $ops_entries[$i]['1.3'];
        $clientLastName = $ops_entries[$i]['1.6'];
        $organisationName = $ops_entries[$i]['17'];
        $transactionDate = $ops_entries[$i]['18'];
        $workCompleted = $ops_entries[$i]['70'];
        $hoursSpent = $ops_entries[$i]['46'];
        $purchased_hours = $ops_entries[$i]['68'];
    
        $new_child_entry = array(
                'form_id' => 151, // The ID of the child form.
                'created_by' => wp_get_current_user(),
                '1' => $clientsEmail,
                '2' => $clientUserID,
                '3.3' => $clientFirstName,
                '3.6' => $clientLastName,
                '4' => $organisationName,
                '5' => $transactionDate,
                '6' => $requestSummary,
                '7' => $workCompleted,
                '8' => $hoursSpent,
                GPNF_Entry::ENTRY_PARENT_KEY => $client_entries_1[0]['id'], // The ID of the parent entry.
                GPNF_Entry::ENTRY_PARENT_FORM_KEY => 150, // The ID of the parent form.
                GPNF_Entry::ENTRY_NESTED_FORM_FIELD_KEY => 6, // The ID of the Nested Form field on the parent form.
            );
                
            $child_entry_id = GFAPI::add_entry( $new_child_entry );
    
        }
    
    
        }
    
}