<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!defined('WPINC')) {
    die;
}
if ( !empty($_REQUEST['offer_name']) && !empty($_REQUEST['category_id']) && !empty($_REQUEST['check_on']) && !empty($_REQUEST['min']) && !empty($_REQUEST['discount_type']) && !empty($_REQUEST['value']) ) {

    $dummy_settings['product_rules'] = array();
    $dummy_settings['combinational_rules'] = array();
    $dummy_settings['category_rules'] = array();
    $dummy_settings['cart_rules'] = array();
    $dummy_settings['buy_get_free_rules'] = array();
    $dummy_settings['BOGO_category_rules'] = array();


    $prev_data = get_option('xa_dp_rules', $dummy_settings);
    if (!isset($prev_data[$active_tab]) || sizeof($prev_data[$active_tab]) == 0) {
        $prev_data[$active_tab][1] = array('offer_name' => sanitize_text_field($_REQUEST['offer_name']),
            'category_id' => $_REQUEST['category_id'],
            'check_on' => sanitize_text_field($_REQUEST['check_on']),
            'min' => sanitize_text_field($_REQUEST['min']),
            'max' => !empty($_REQUEST['max']) ? $_REQUEST['max'] : NULL,
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
        );
    } else {
        $prev_data[$active_tab][] = array('offer_name' => sanitize_text_field($_REQUEST['offer_name']),
            'category_id' => $_REQUEST['category_id'],
            'check_on' => sanitize_text_field($_REQUEST['check_on']),
            'min' => sanitize_text_field($_REQUEST['min']),
            'max' => !empty($_REQUEST['max']) ? $_REQUEST['max'] : NULL,
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
