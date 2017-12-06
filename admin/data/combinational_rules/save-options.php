<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!defined('WPINC')) {
    die;
}
//wp_die('<pre>'.print_r($_REQUEST,true).'</pre>');
if ( !empty($_REQUEST['offer_name']) && !empty($_REQUEST['product_id1']) && !empty($_REQUEST['quantity1']) && !empty($_REQUEST['discount_type']) && !empty($_REQUEST['value']) && !isset($_REQUEST['edit'])) {

    $dummy_settings['product_rules'] = array();
    $dummy_settings['combinational_rules'] = array();
    $dummy_settings['category_rules'] = array();
    $dummy_settings['cart_rules'] = array();
    $dummy_settings['buy_get_free_rules'] = array();
    $dummy_settings['BOGO_category_rules'] = array();


    $prev_data = get_option('xa_dp_rules', $dummy_settings);
    $pid_field = 'product_id1';
    $qnty_field = 'quantity1';
    $product_id_array = array();
    $fieldcount = 1;
    while (isset($_REQUEST[$pid_field]) && !empty($_REQUEST[$pid_field]) && isset($_REQUEST[$qnty_field]) && !empty($_REQUEST[$qnty_field])) {
        $fieldcount++;
        $product_id_array[$_REQUEST[$pid_field]] = $_REQUEST[$qnty_field];
        $pid_field = 'product_id' . $fieldcount;
        $qnty_field = 'quantity' . $fieldcount;
    }
    if (!isset($prev_data[$active_tab]) || sizeof($prev_data[$active_tab]) == 0) {
        $prev_data[$active_tab][1] = array('offer_name' => sanitize_text_field($_REQUEST['offer_name']),
            'product_id' => $product_id_array,
            'discount_type' => sanitize_text_field($_REQUEST['discount_type']),
            'value' => sanitize_text_field($_REQUEST['value']),
            'max_discount' => !empty($_REQUEST['max_discount']) ? $_REQUEST['max_discount'] : NULL,
            'allow_roles' => (!empty($_REQUEST['allow_roles']) ? $_REQUEST['allow_roles'] : array()),
            'from_date' => !empty($_REQUEST['from_date']) ? $_REQUEST['from_date'] : NULL,
            'to_date' => !empty($_REQUEST['to_date']) ? $_REQUEST['to_date'] : NULL,
            'adjustment' => !empty($_REQUEST['adjustment']) ? sanitize_text_field($_REQUEST['adjustment']) : NULL,
            'email_ids' => !empty($_REQUEST['email_ids']) ? sanitize_text_field($_REQUEST['email_ids']) : NULL,
            'prev_order_count' => !empty($_REQUEST['prev_order_count']) ? sanitize_text_field($_REQUEST['prev_order_count']) : NULL,
            'prev_order_total_amt' => !empty($_REQUEST['prev_order_total_amt']) ? sanitize_text_field($_REQUEST['prev_order_total_amt']) : NULL,
            'discount_on_product_id' => !empty($_REQUEST['discount_on_product_id']) ? $_REQUEST['discount_on_product_id'] : NULL,
        );
    } else {
        $prev_data[$active_tab][] = array('offer_name' => sanitize_text_field($_REQUEST['offer_name']),
            'product_id' => $product_id_array,
            'discount_type' => sanitize_text_field($_REQUEST['discount_type']),
            'value' => sanitize_text_field($_REQUEST['value']),
            'max_discount' => !empty($_REQUEST['max_discount']) ? $_REQUEST['max_discount'] : NULL,
            'allow_roles' => (!empty($_REQUEST['allow_roles']) ? $_REQUEST['allow_roles'] : array()),
            'from_date' => !empty($_REQUEST['from_date']) ? $_REQUEST['from_date'] : NULL,
            'to_date' => !empty($_REQUEST['to_date']) ? $_REQUEST['to_date'] : NULL,
            'adjustment' => !empty($_REQUEST['adjustment']) ? sanitize_text_field($_REQUEST['adjustment']) : NULL,
            'email_ids' => !empty($_REQUEST['email_ids']) ? sanitize_text_field($_REQUEST['email_ids']) : NULL,
            'prev_order_count' => !empty($_REQUEST['prev_order_count']) ? sanitize_text_field($_REQUEST['prev_order_count']) : NULL,
            'prev_order_total_amt' => !empty($_REQUEST['prev_order_total_amt']) ? sanitize_text_field($_REQUEST['prev_order_total_amt']) : NULL,
            'discount_on_product_id' => !empty($_REQUEST['discount_on_product_id']) ? $_REQUEST['discount_on_product_id'] : NULL,
        );
    }

    update_option('xa_dp_rules', $prev_data);
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Saved Successfully', 'eh-dynamic-pricing-discounts'); ?></p>
    </div>
    <?php
    wp_safe_redirect(admin_url('admin.php?page=dynamic-pricing-main-page&tab=' . $active_tab));
} else {
    echo '<div class="notice notice-error is-dismissible">';
    echo '<p>' . _e('Please Enter All Fields!! Then Save', 'eh-dynamic-pricing-discounts') . '</p> </div>';
}
