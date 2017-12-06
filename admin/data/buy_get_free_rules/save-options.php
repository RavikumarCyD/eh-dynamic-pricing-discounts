<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!defined('WPINC')) {
    die;
}
if (isset($_REQUEST['offer_name']) && !empty($_REQUEST['offer_name']) && isset($_REQUEST['purchased_product_id1']) && !empty($_REQUEST['purchased_product_id1']) && isset($_REQUEST['purchased_quantity1']) && !empty($_REQUEST['purchased_quantity1']) && isset($_REQUEST['free_product_id1']) && !empty($_REQUEST['free_product_id1']) && isset($_REQUEST['free_quantity1']) && !empty($_REQUEST['free_quantity1']) && !isset($_REQUEST['edit'])) {

    $dummy_settings['product_rules'] = array();
    $dummy_settings['combinational_rules'] = array();
    $dummy_settings['category_rules'] = array();
    $dummy_settings['cart_rules'] = array();
    $dummy_settings['buy_get_free_rules'] = array();
    $dummy_settings['BOGO_category_rules'] = array();


    $prev_data = get_option('xa_dp_rules', $dummy_settings);
    $pid_field = 'purchased_product_id1';
    $qnty_field = 'purchased_quantity1';
    $purchased_product_id_array = array();
    $fieldcount = 1;
    while (isset($_REQUEST[$pid_field]) && !empty($_REQUEST[$pid_field]) && isset($_REQUEST[$qnty_field]) && !empty($_REQUEST[$qnty_field])) {
        $fieldcount++;
        $purchased_product_id_array[$_REQUEST[$pid_field]] = $_REQUEST[$qnty_field];
        $pid_field = 'purchased_product_id' . $fieldcount;
        $qnty_field = 'purchased_quantity' . $fieldcount;
    }
    $pid_field = 'free_product_id1';
    $qnty_field = 'free_quantity1';
    $free_product_id_array = array();
    $fieldcount = 1;
    while (isset($_REQUEST[$pid_field]) && !empty($_REQUEST[$pid_field]) && isset($_REQUEST[$qnty_field]) && !empty($_REQUEST[$qnty_field])) {
        $fieldcount++;
        $free_product_id_array[$_REQUEST[$pid_field]] = $_REQUEST[$qnty_field];
        $pid_field = 'free_product_id' . $fieldcount;
        $qnty_field = 'free_quantity' . $fieldcount;
    }
    if (!isset($prev_data[$active_tab]) || sizeof($prev_data[$active_tab]) == 0) {
        $prev_data[$active_tab][1] = array('offer_name' => $_REQUEST['offer_name'],
            'purchased_product_id' => $purchased_product_id_array,
            'free_product_id' => $free_product_id_array,
            'allow_roles' => !empty($_REQUEST['allow_roles']) ? $_REQUEST['allow_roles'] : array(),
            'from_date' => !empty($_REQUEST['from_date']) ? $_REQUEST['from_date'] : NULL,
            'to_date' => !empty($_REQUEST['to_date']) ? $_REQUEST['to_date'] : NULL,
            'adjustment' => !empty($_REQUEST['adjustment']) ? sanitize_text_field($_REQUEST['adjustment']) : NULL,
            'email_ids' => !empty($_REQUEST['email_ids']) ? sanitize_text_field($_REQUEST['email_ids']) : NULL,
            'prev_order_count' => !empty($_REQUEST['prev_order_count']) ? sanitize_text_field($_REQUEST['prev_order_count']) : NULL,
            'prev_order_total_amt' => !empty($_REQUEST['prev_order_total_amt']) ? sanitize_text_field($_REQUEST['prev_order_total_amt']) : NULL,
            'repeat_rule'  => !empty($_REQUEST['repeat_rule']) ? sanitize_text_field($_REQUEST['repeat_rule']) : 'no',
        );
    } else {
        $prev_data[$active_tab][] = array('offer_name' => $_REQUEST['offer_name'],
            'purchased_product_id' => $purchased_product_id_array,
            'free_product_id' => $free_product_id_array,
            'allow_roles' => !empty($_REQUEST['allow_roles']) ? $_REQUEST['allow_roles'] : array(),
            'from_date' => !empty($_REQUEST['from_date']) ? $_REQUEST['from_date'] : NULL,
            'to_date' => !empty($_REQUEST['to_date']) ? $_REQUEST['to_date'] : NULL,
            'adjustment' => !empty($_REQUEST['adjustment']) ? sanitize_text_field($_REQUEST['adjustment']) : NULL,
            'email_ids' => !empty($_REQUEST['email_ids']) ? sanitize_text_field($_REQUEST['email_ids']) : NULL,
            'prev_order_count' => !empty($_REQUEST['prev_order_count']) ? sanitize_text_field($_REQUEST['prev_order_count']) : NULL,
            'prev_order_total_amt' => !empty($_REQUEST['prev_order_total_amt']) ? sanitize_text_field($_REQUEST['prev_order_total_amt']) : NULL,
            'repeat_rule'  => !empty($_REQUEST['repeat_rule']) ? sanitize_text_field($_REQUEST['repeat_rule']) : 'no',
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
