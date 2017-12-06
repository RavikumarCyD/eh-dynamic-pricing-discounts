<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!defined('WPINC')) {
    die;
}
if (isset($_GET['offer_name']) && !empty($_GET['offer_name']) && isset($_GET['check_on']) && !empty($_GET['check_on']) && isset($_GET['min']) && !empty($_GET['min']) && isset($_GET['discount_type']) && !empty($_GET['discount_type']) && isset($_GET['value']) && !empty($_GET['value']) && !isset($_GET['edit'])) {
    $dummy_settings['product_rules'] = array();
    $dummy_settings['combinational_rules'] = array();
    $dummy_settings['category_rules'] = array();
    $dummy_settings['cart_rules'] = array();
    $dummy_settings['buy_get_free_rules'] = array();
    $dummy_settings['BOGO_category_rules'] = array();
    
    $attributes=array();
    if(!empty($_REQUEST['at_taxonomy']) && !empty($_REQUEST['at_val']) )
    {
        $attributes['at_taxonomy']=array_values( $_REQUEST['at_taxonomy']);
        $attributes['at_val']=array_values( $_REQUEST['at_val']);
    }

    $prev_data = get_option('xa_dp_rules', $dummy_settings);
    if (!isset($prev_data[$active_tab]) || sizeof($prev_data[$active_tab]) == 0) {
        $prev_data[$active_tab][1] = array('offer_name' => sanitize_text_field($_GET['offer_name']),
            'check_on' => sanitize_text_field($_GET['check_on']),
            'min' => sanitize_text_field($_GET['min']),
            'max' => !empty($_GET['max']) ? $_GET['max'] : NULL,
            'discount_type' => sanitize_text_field($_GET['discount_type']),
            'value' => sanitize_text_field($_GET['value']),
            'max_discount' => !empty($_GET['max_discount']) ? $_GET['max_discount'] : NULL,
            'allow_roles' => (!empty($_GET['allow_roles']) ? $_GET['allow_roles'] : array()),
            'from_date' => !empty($_GET['from_date']) ? $_GET['from_date'] : NULL,
            'to_date' => !empty($_GET['to_date']) ? $_GET['to_date'] : NULL,
            'adjustment' => !empty($_GET['adjustment']) ? sanitize_text_field($_GET['adjustment']) : NULL,
            'email_ids' => !empty($_GET['email_ids']) ? sanitize_text_field($_GET['email_ids']) : NULL,
            'prev_order_count' => !empty($_GET['prev_order_count']) ? sanitize_text_field($_GET['prev_order_count']) : NULL,
            'prev_order_total_amt' => !empty($_GET['prev_order_total_amt']) ? sanitize_text_field($_GET['prev_order_total_amt']) : NULL,
            'attributes'    => $attributes,
            'attributes_mode' => !empty($_GET['attributes_mode']) ? sanitize_text_field($_GET['attributes_mode']) : NULL,
            'allowed_payment_methods' => !empty($_GET['allowed_payment_methods']) ? (array) $_GET['allowed_payment_methods'] : NULL,
            'allowed_shipping_methods' => !empty($_GET['allowed_shipping_methods']) ? (array) $_GET['allowed_shipping_methods'] : NULL,
            'minimum_stock_limit' => !empty($_GET['minimum_stock_limit']) ? $_GET['minimum_stock_limit'] : NULL,
            'maximum_stock_limit' => !empty($_GET['maximum_stock_limit']) ? $_GET['maximum_stock_limit'] : NULL,
            );
    } else {
        $prev_data[$active_tab][] = array('offer_name' => sanitize_text_field($_GET['offer_name']),
            'check_on' => sanitize_text_field($_GET['check_on']),
            'min' => sanitize_text_field($_GET['min']),
            'max' => !empty($_GET['max']) ? $_GET['max'] : NULL,
            'discount_type' => sanitize_text_field($_GET['discount_type']),
            'value' => sanitize_text_field($_GET['value']),
            'max_discount' => !empty($_GET['max_discount']) ? $_GET['max_discount'] : NULL,
            'allow_roles' => (!empty($_GET['allow_roles']) ? $_GET['allow_roles'] : array()),
            'from_date' => !empty($_GET['from_date']) ? $_GET['from_date'] : NULL,
            'to_date' => !empty($_GET['to_date']) ? $_GET['to_date'] : NULL,
            'adjustment' => !empty($_GET['adjustment']) ? sanitize_text_field($_GET['adjustment']) : NULL,
            'email_ids' => !empty($_GET['email_ids']) ? sanitize_text_field($_GET['email_ids']) : NULL,
            'prev_order_count' => !empty($_GET['prev_order_count']) ? sanitize_text_field($_GET['prev_order_count']) : NULL,
            'prev_order_total_amt' => !empty($_GET['prev_order_total_amt']) ? sanitize_text_field($_GET['prev_order_total_amt']) : NULL,
            'attributes'    => $attributes,
            'attributes_mode' => !empty($_GET['attributes_mode']) ? sanitize_text_field($_GET['attributes_mode']) : NULL,
            'allowed_payment_methods' => !empty($_GET['attributes_allowed_payment_methodsmode']) ? (array) $_GET['allowed_payment_methods'] : NULL,
            'minimum_stock_limit' => !empty($_GET['minimum_stock_limit']) ? $_GET['minimum_stock_limit'] : NULL,
            'maximum_stock_limit' => !empty($_GET['maximum_stock_limit']) ? $_GET['maximum_stock_limit'] : NULL,
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
