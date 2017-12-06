<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (sizeof($old_option) > 0 && !isset($_REQUEST['submit'])) {
    $record_no = $_REQUEST['edit'];
    $selected_record = $old_option[$record_no];
    $_REQUEST = array_merge($_REQUEST, $selected_record);
    $count = 1;
    foreach ($selected_record['product_id'] as $pkey => $qnty) {
        $_REQUEST['product_id' . $count] = $pkey;
        $_REQUEST['quantity' . $count] = $qnty;
        $count++;
    }
$dummy_option = array('product_rules' => array(), 'combinational_rules' => array(), 'cat_combinational_rules' => array(), 'category_rules' => array(), 'cart_rules' => array());
$rules_option_array = get_option('xa_dp_rules', $dummy_option);
$defaultOptions = array();
$customOptions =isset($rules_option_array[$active_tab][$_REQUEST['edit']])?$rules_option_array[$active_tab][$_REQUEST['edit']]:array();
$_REQUEST = array_merge($_REQUEST, $customOptions);
}
