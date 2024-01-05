<?php

/*
 * On submission, recalculate opening and closing balances for each entry
 * in Form ID 50 where client user ID is common
 * */

add_action('gform_after_submission_138', 'recalculate_customer_balances_form_id_50', 10, 2);
function recalculate_customer_balances_form_id_50($entry, $form)
{

    bs('gform_138_submission');

    include BS_NAME_PLUGIN_DIR . 'gform-138/reset_balances.php';

}
