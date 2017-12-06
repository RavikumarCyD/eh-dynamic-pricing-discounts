<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!defined('WPINC')) {
    die;
}
    if (isset($_REQUEST['offer_name']) && !empty($_REQUEST['offer_name']) && isset($_REQUEST['purchased_category_id1']) && !empty($_REQUEST['purchased_category_id1']) && isset($_REQUEST['free_product_id1']) && !empty($_REQUEST['free_product_id1']) && isset($_REQUEST['purchased_quantity1']) && !empty($_REQUEST['purchased_quantity1']) && isset($_REQUEST['free_quantity1']) && !empty($_REQUEST['free_quantity1']) && isset($_REQUEST['update']) && !empty($_REQUEST['update'])) {


        $prev_data = get_option('xa_dp_rules');
        $pid_field = 'purchased_category_id1';
        $checkon_field = 'purchased_checkon1';
        $qnty_field = 'purchased_quantity1';
        $purchased_category_id_array = array();
        $fieldcount = 1;
        while (isset($_REQUEST[$pid_field]) && !empty($_REQUEST[$pid_field]) && isset($_REQUEST[$qnty_field]) && !empty($_REQUEST[$qnty_field])) {
            $fieldcount++;
            $ppid=isset($_REQUEST[$pid_field])?$_REQUEST[$pid_field]:'';
            $checkon=isset($_REQUEST[$checkon_field])?$_REQUEST[$checkon_field]:'items';
            $qty=isset($_REQUEST[$qnty_field])?$_REQUEST[$qnty_field]:'0';
            $purchased_category_id_array[$ppid] = $qty.":".$checkon;
            $pid_field = 'purchased_category_id' . $fieldcount;
            $checkon_field = 'purchased_checkon' . $fieldcount;
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

        $prev_data[$active_tab][$_REQUEST['update']] = array('offer_name' => $_REQUEST['offer_name'],
            'purchased_category_id' => $purchased_category_id_array,
            'free_product_id' => $free_product_id_array,
            'allow_roles' => !empty($_REQUEST['allow_roles']) ? $_REQUEST['allow_roles'] : array(),
            'from_date' => !empty($_REQUEST['from_date']) ? $_REQUEST['from_date'] : NULL,
            'to_date' => !empty($_REQUEST['to_date']) ? $_REQUEST['to_date'] : NULL,
            'adjustment' => !empty($_REQUEST['adjustment']) ? sanitize_text_field($_REQUEST['adjustment']) : NULL,
            'email_ids' => !empty($_REQUEST['email_ids']) ? sanitize_text_field($_REQUEST['email_ids']) : NULL,
            'prev_order_count' => !empty($_REQUEST['prev_order_count']) ? sanitize_text_field($_REQUEST['prev_order_count']) : NULL,
            'prev_order_total_amt' => !empty($_REQUEST['prev_order_total_amt']) ? sanitize_text_field($_REQUEST['prev_order_total_amt']) : NULL,
        );
        update_option('xa_dp_rules', $prev_data);
        $_REQUEST = array();
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Updated Successfully', 'eh-dynamic-pricing-discounts'); ?></p>
        </div>
        <?php
        wp_safe_redirect(admin_url('admin.php?page=dynamic-pricing-main-page&tab=' . $active_tab));
    } else {
        echo '<div class="notice notice-error is-dismissible">';
        echo '<p>' . _e('Please Enter All Fields ,Then Try To Update!!', 'eh-dynamic-pricing-discounts') . '</p> </div>';
    }