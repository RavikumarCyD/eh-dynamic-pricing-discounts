<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (sizeof($old_option) > 0 && !isset($_REQUEST['submit'])) {
    $record_no = $_REQUEST['edit'];
    $selected_record = $old_option[$record_no];
    $_REQUEST = array_merge($_REQUEST, $selected_record);
    $count = 1;
    foreach ($selected_record['cat_id'] as $pkey => $qnty) {
        $_REQUEST['cat_id' . $count] = $pkey;
        $_REQUEST['quantity' . $count] = $qnty;
        $count++;
    }
}
