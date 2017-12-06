<?php
if (isset($_REQUEST['edit']) && !empty($_REQUEST['edit'])) {
    echo '<input type="hidden" name="update" value="' . $_REQUEST['edit'] . '" >';
}
?>   
<script>
    jQuery(document).ready(function () {
        jQuery('#check_on').on('change', function () {
            let thisval = jQuery('#check_on').val();
            jQuery("[for=min]").html('Minimum ' + thisval + '<span style="color:red;padding-left:5px">*<span>');
            jQuery("[for=max]").html('Maximum ' + thisval);
        });
        jQuery('#discount_type').on('change', function () {
            let thisval = jQuery('#discount_type').val();
            if (thisval == 'Percent Discount')
            {
                jQuery('#max_discount').parent().show();
                jQuery("[for=value]").html('<?php _e('Discount percentage','eh-dynamic-pricing-discounts'); ?>' + '<span style="color:red;padding-left:5px">*<span>');
            } else if (thisval == 'Flat Discount')
            {
                jQuery("[for=value]").html('Flat discount amount' + '<span style="color:red;padding-left:5px">*<span>');
                jQuery('#max_discount').val('');
                jQuery('#max_discount').parent().hide();
            } else if (thisval == 'Fixed Price')
            {
                jQuery('#max_discount').parent().show();
                jQuery("[for=value]").html('Fixed price' + '<span style="color:red;padding-left:5px">*<span>');
            }
        });
        jQuery('#rule_on').on('change', function () {
            let selected = jQuery('#rule_on').val();

            jQuery('#product_id').removeAttr('required');
            if (selected == 'products')
            {

                jQuery('#category_id').parent().hide();
                jQuery('#product_id').parent().show();
                jQuery('#product_id').attr('required', 'required');
            } else if (selected == 'categories')
            {
                jQuery("#product_id").empty();
                jQuery('#product_id').parent().hide();
                jQuery('#category_id').parent().show();
            } else if (selected == 'cart')
            {
                jQuery('#product_id').parent().hide();
                jQuery('#category_id').parent().hide();
            }
        });
        jQuery('#rule_on').trigger('change');
        jQuery('#rule_tab label').append('<span style="color:red;padding-left:5px">*<span>');
        jQuery('#check_on').trigger('change');
        jQuery("[for=max]").html(jQuery("[for=max]").text());
        jQuery('#discount_type').trigger('change');

    });

</script>
<div >
    <div id="normal-sortables" class="meta-box-sortables ui-sortable">

        </br>

        <div class="clear"></div>
        <div id="woocommerce-product-data" class="postbox ">
            <div class="inside">
                <div class="panel-wrap product_data" style="min-height: 390px;">
                    <ul class="product_data_tabs wc-tabs">
                        <li class="rule_options   active">
                            <a class="xa_link" onclick="select(this, '#rule_tab')">
                                <span><?php _e('Rule','eh-dynamic-pricing-discounts'); ?></span>
                            </a>
                        </li>
                        <li class="adjustment_options">
                            <a class="xa_link" onclick="select(this, '#adjustment_tab')">
                                <span><?php _e('Adjustments','eh-dynamic-pricing-discounts'); ?></span>
                            </a>
                        </li>
                        <li class="roles_options " style="display: block;">
                            <a class="xa_link" onclick="select(this, '#allowed_roles_and_date_tab')">
                                <span><?php _e('Allowed Roles & Date','eh-dynamic-pricing-discounts'); ?></span>
                            </a>
                        </li>
                        <li class="restricion_options " style="display: block;">
                            <a class="xa_link" onclick="select(this, '#restriction_tab')">
                                <span><?php _e('Restrictions','eh-dynamic-pricing-discounts'); ?></span>
                            </a>
                        </li>

                    </ul>
                    <div  class="panel woocommerce_options_panel" style="display: block;">
                        <div class="options_group" id="rule_tab" style="display: block;">
                            <?php
                            woocommerce_wp_text_input(array(
                                'id' => 'offer_name',
                                'label' => __('Offer name', 'eh-dynamic-pricing-discounts'),
                                'placeholder' => __('Enter a descriptive offer name','eh-dynamic-pricing-discounts'),
                                'description' => __('Name/Text of the offer to be displayed in the Offer Table. We suggest a detailed description of the discount.', 'eh-dynamic-pricing-discounts'),
                                'type' => 'text',
                                'desc_tip' => true,
                                'value' => !empty($_REQUEST['offer_name']) ? $_REQUEST['offer_name'] : '',
                                'custom_attributes' => array('required' => 'required')
                            ));
                            woocommerce_wp_select(array(
                                'id' => 'rule_on',
                                'label' => __('Rule applicable on', 'eh-dynamic-pricing-discounts'),
                                'options' => array('products' => 'Selected Products',
                                    'categories' => 'All Products in Category',
                                    'cart' => 'All Products in Cart',),
                                'value' => !empty($_REQUEST['rule_on']) ? $_REQUEST['rule_on'] : 'products',
                                'description' => __('<u>Selected products:</u>The rule would be applied to the selected products individually.</br><u>Selected category:</u>This is different from the "Category Rule". In this case, the rule would be individually applied to all the products in the category.</br><u>Products in Cart:</u>Rule will be applied individually on each product in cart', 'eh-dynamic-pricing-discounts'),
                                'desc_tip' => true,
                            ));
                            ///// start product search     
                            if (is_wc_version_gt_eql('2.7')) {
                                ?>
                                <p class="form-field"><label><?php _e('Products', 'eh-dynamic-pricing-discounts'); ?></label>
                                    <select class="wc-product-search" multiple="multiple" style="width: 50%;height:30px" id="product_id" name="product_id[]" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'eh-dynamic-pricing-discounts'); ?>" data-action="woocommerce_json_search_products_and_variations">
                                        <?php
                                        $product_ids = !empty($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();  // selected product ids
                                        foreach ($product_ids as $product_id) {
                                            $product = wc_get_product($product_id);
                                            if (is_object($product)) {
                                                echo '<option value="' . esc_attr($product_id) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select> <?php echo wc_help_tip(__('The rule would be applied to the selected products individually.', 'eh-dynamic-pricing-discounts')); ?></p>
                                <?php
                            } else {
                                ?>
                                <p class="form-field"><label><?php _e('Products', 'eh-dynamic-pricing-discounts'); ?></label>
                                    <input id="product_id" name="product_id" type="hidden" class="wc-product-search" data-multiple="true" style="width: 50%;"  data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'eh-dynamic-pricing-discounts'); ?>" data-action="woocommerce_json_search_products_and_variations" data-selected="<?php
                                    $product_ids = (!empty($_REQUEST['product_id']) && is_array($_REQUEST['product_id'])) ? $_REQUEST['product_id'] : array();  // selected product ids
                                    $json_ids = array();
                                    foreach ($product_ids as $product_id) {
                                        $product = wc_get_product($product_id);
                                        if (is_object($product)) {
                                            $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                                        }
                                    }

                                    echo esc_attr(json_encode($json_ids));
                                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" /> <?php echo wc_help_tip(__('Rule to be applied on which products', 'eh-dynamic-pricing-discounts')); ?></p>
                                    <?php
                                }
                                // start Categories  search
                                ?>
                            <p class="form-field"><label for="category_id"><?php _e('Product categories', 'eh-dynamic-pricing-discounts'); ?></label>
                                <select id="category_id" name="category_id" style="width: 50%;height:30px"  class="wc-enhanced-select"  data-placeholder="<?php esc_attr_e('Any category', 'eh-dynamic-pricing-discounts'); ?>">
                                    <?php
                                    $category_ids = !empty($_REQUEST['category_id']) ? $_REQUEST['category_id'] : '';  //selected product categorie
                                    $categories = get_terms('product_cat', 'orderby=name&hide_empty=0');
                                    if ($categories) {
                                        foreach ($categories as $cat) {
                                            echo '<option value="' . esc_attr($cat->term_id) . '"' . selected($cat->term_id == $category_ids, true, false) . '>' . esc_html($cat->name) . '</option>';
                                        }
                                    }
                                    ?>
                                </select> <?php echo wc_help_tip(__('This is different from the "Category Rule". In this case, the rule would be individually applied to all the products in the category.', 'eh-dynamic-pricing-discounts')); ?></p>
                            <?php
                            //// end category search
                            woocommerce_wp_select(array(
                                'id' => 'check_on',
                                'label' => __('Check for', 'eh-dynamic-pricing-discounts'),
                                'options' => array('Quantity' => 'Quantity',
                                    'Weight' => 'Weight',
                                    'Price' => 'Price',),
                                'value' => !empty($_REQUEST['check_on']) ? $_REQUEST['check_on'] : 'Quantity',
                                'description' => __('The rules can be applied based on "Quantity/Price/Weight"', 'eh-dynamic-pricing-discounts'),
                                'desc_tip' => true,
                            ));
                            woocommerce_wp_text_input(array(
                                'id' => 'min',
                                'label' => __('Minimum', 'eh-dynamic-pricing-discounts'),
                                'description' => __('Minimum value to check', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'value' => !empty($_REQUEST['min']) ? $_REQUEST['min'] : '1',
                                'custom_attributes' => array('required' => 'required','step'=>'any')
                            ));
                            woocommerce_wp_text_input(array(
                                'id' => 'max',
                                'label' => __('Maximum', 'eh-dynamic-pricing-discounts'),
                                'description' => __('Maximum value to check, set it empty for no limit', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'value' => !empty($_REQUEST['max']) ? $_REQUEST['max'] : '',
                                'custom_attributes' => array('step'=>'any')

                            ));
                            woocommerce_wp_select(array(
                                'id' => 'discount_type',
                                'label' => __('Discount type', 'eh-dynamic-pricing-discounts'),
                                'options' => array('Percent Discount' => 'Percent Discount',
                                    'Flat Discount' => 'Flat Discount',
                                    'Fixed Price' => 'Fixed Price',),
                                'value' => !empty($_REQUEST['discount_type']) ? $_REQUEST['discount_type'] : 'Percent Discount',
                                'description' => __('Three types of discounts can be applied – "Percentage Discount/Flat Discount/Fixed Price"', 'eh-dynamic-pricing-discounts'),
                                'desc_tip' => true,
                            ));
                            woocommerce_wp_text_input(array(
                                'id' => 'value',
                                'label' => __('Discount', 'eh-dynamic-pricing-discounts'),
                                'description' => __('If you select "Percentage Discount", the given percentage (value) would be discounted on each unit of the product in the cart.
If you select "Flat Discount", the given amount (value) would be discounted at subtotal level in the cart
If you select "Fixed Price", the original price of the product is replaced by the given fixed price (value).', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'value' => !empty($_REQUEST['value']) ? $_REQUEST['value'] : '',
                                'custom_attributes' => array('required' => 'required',"step"=>"any")
                            ));
                            ?>
                        </div>
                        <div class="options_group" id="adjustment_tab" style="display: none;">
                            <?php
                            woocommerce_wp_text_input(array(
                                'id' => 'max_discount',
                                'label' => __('Maximum discount amount', 'eh-dynamic-pricing-discounts'),
                                'description' => __('This value is used to set up a limit for the discount. This is usually left blank if you do not want to limit the discount.', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'value' => !empty($_REQUEST['max_discount']) ? $_REQUEST['max_discount'] : '',
                                'custom_attributes' => array("step"=>"any")
                            ));
                            woocommerce_wp_text_input(array(
                                'id' => 'adjustment',
                                'label' => __('Adjustment amount', 'eh-dynamic-pricing-discounts'),
                                'description' => __('Adjust final discount amount by this amount', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'value' => !empty($_REQUEST['adjustment']) ? $_REQUEST['adjustment'] : '',
                                'custom_attributes' => array("step"=>"any")
                            ));
                            woocommerce_wp_checkbox(array(
                                'id' => 'repeat_rule',
                                'label' => __('Allow repeat execution', 'eh-dynamic-pricing-discounts'),
                                'description' =>  __('This rule will be executed if the total quantity of the product is a multiple of the min/max quantity set in the "Rule" section. Note that for this to work the min and the max quantity has to be equal.', 'eh-dynamic-pricing-discounts'),
                                'value' => !empty($_REQUEST['repeat_rule']) ? $_REQUEST['repeat_rule'] : 'on',
                            ));
                            ?>
                        </div>
                        <div class="options_group" id="allowed_roles_and_date_tab" style="display: none;">
                            <?php
                            global $wp_roles;
                            $roles = $wp_roles->get_names();
                            $role_all = __('All', 'eh-dynamic-pricing-discounts');
                            $roles=array_merge(array('all' => $role_all),$roles);
                            ?>
                            <p class="form-field allow_roles[]_field ">
                            <label for="allow_roles[]">Allowed Roles</label>
                            <span class="woocommerce-help-tip"></span>
                            <select id="allow_roles[]" name="allow_roles[]" class="roles_select select2-hidden-accessible" style="width:50%;" multiple="" tabindex="-1" aria-hidden="true">
                            <?php 
                            $selected=!empty($_REQUEST['allow_roles'])?$_REQUEST['allow_roles']:array();
                            if(!array($selected)){
                                $selected=array($selected);
                            }
                            foreach($roles as $key=>$val)
                            {
                                $is_selected=in_array($key,$selected)?" selected ":" ";
                                echo "<option value='$key' ".$is_selected." >$val</option>";
                            }
                            ?>
                            </select>    
                            </p>    
                            <?php

                            woocommerce_wp_text_input(array(
                                'id' => 'from_date',
                                'value' => esc_attr(!empty($_REQUEST['from_date']) ? $_REQUEST['from_date'] : ''),
                                'label' => __('Valid from date', 'eh-dynamic-pricing-discounts'),
                                'placeholder' => 'YYYY-MM-DD',
                                'description' => 'The date from which the rule would be applied. This can be left blank if do not wish to set up any date range.',
                                'desc_tip' => true,
                                'class' => 'date-picker',
                                'custom_attributes' => array(
                                    'pattern' => apply_filters('woocommerce_date_input_html_pattern', '(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}'),
                                ),
                            ));
                            woocommerce_wp_text_input(array(
                                'id' => 'to_date',
                                'value' => esc_attr(!empty($_REQUEST['to_date']) ? $_REQUEST['to_date'] : ''),
                                'label' => __('Expiry date', 'eh-dynamic-pricing-discounts'),
                                'placeholder' => 'YYYY-MM-DD',
                                'description' => ' The date till which the rule would be valid. You can leave it blank if you wish the rule to be applied forever or would like to end it manually.',
                                'desc_tip' => true,
                                'class' => 'date-picker',
                                'custom_attributes' => array(
                                    'pattern' => apply_filters('woocommerce_date_input_html_pattern', '(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}'),
                                ),
                            ));
                            ?>
                        </div>
                        <div class="options_group" id="restriction_tab" style="display: none;">
                            <?php
                            woocommerce_wp_text_input(array(
                                'id' => 'email_ids',
                                'label' => __('Allowed Email Ids', 'eh-dynamic-pricing-discounts'),
                                'placeholder' => __('Enter Email ids seperated by commas', 'eh-dynamic-pricing-discounts'),
                                'description' => __('Enter Email ids seperated by commas, for which you want to allow this rule. and leave blank to allow for all', 'eh-dynamic-pricing-discounts'),
                                'type' => 'text',
                                'desc_tip' => true,
                                'value' => !empty($_REQUEST['email_ids']) ? $_REQUEST['email_ids'] : ''
                            ));
                            woocommerce_wp_text_input(array(
                                'id' => 'prev_order_count',
                                'label' => __('Minimum number of orders (previous orders)', 'eh-dynamic-pricing-discounts'),
                                'description' => __('Minimum count of preivious orders required for this rule to be executed', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'custom_attributes' => array(
                                    'step' => 1,
                                    'min' => 0,
                                ),
                                'value' => !empty($_REQUEST['prev_order_count']) ? $_REQUEST['prev_order_count'] : ''
                            ));
                            woocommerce_wp_text_input(array(
                                'id' => 'prev_order_total_amt',
                                'label' => __('Minimum total spending (previous orders)', 'eh-dynamic-pricing-discounts'),
                                'description' => __('Minimum amount the user has spent till now for the rule to execute. total calculated from all previous orders', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'custom_attributes' => array(
                                    'step' => 1,
                                    'min' => 0,
                                ),
                                'value' => !empty($_REQUEST['prev_order_total_amt']) ? $_REQUEST['prev_order_total_amt'] : ''
                            ));
                            ?>
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>
