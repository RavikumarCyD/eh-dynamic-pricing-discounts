<?php
if (isset($_REQUEST['edit']) && !empty($_REQUEST['edit'])) {
    echo '<input type="hidden" name="update" value="' . $_REQUEST['edit'] . '" >';
}
?>   
<script>
    jQuery(document).ready(function () {
        jQuery('#check_on').on('change', function () {
            let thisval = jQuery('#check_on').val().replace('TotalQuantity', 'Total Units').replace('Quantity', 'No of Items');
            jQuery("[for=min]").html('Minimum ' + thisval + '<span style="color:red;padding-left:5px">*<span>');
            jQuery("[for=max]").html('Maximum ' + thisval);
        });
        jQuery('#update').on('click', function () {
            if(jQuery('#category_id').find(":selected").length<=0)
            {
                alert('Please select a category before saving');
                return false;
            }   
            return true;
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
        jQuery('#rule_tab label').append('<span style="color:red;padding-left:5px">*<span>');
        jQuery('#discount_type').trigger('change');
        jQuery('#check_on').trigger('change');
        jQuery("[for=max]").html(jQuery("[for=max]").text());



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

                            // start Categories  search
                            ?>
                            <p class="form-field"><label for="category_id"><?php _e('Product categories', 'eh-dynamic-pricing-discounts'); ?></label>
                                <select id="category_id" name="category_id[]" style="width: 50%;height:30px" multiple class="wc-enhanced-select"  data-placeholder="<?php esc_attr_e('Select category', 'eh-dynamic-pricing-discounts'); ?>">
                                    <?php
                                    $category_ids = !empty($_REQUEST['category_id']) ? $_REQUEST['category_id'] : array();  //selected product categorie
                                    if(!is_array($category_ids)) $category_ids=array($category_ids);
                                    $categories = get_terms('product_cat', 'orderby=name&hide_empty=0');
                                    if ($categories) {
                                        foreach ($categories as $cat) {
                                            echo '<option value="' . esc_attr($cat->term_id) . '"' . selected(in_array($cat->term_id,$category_ids), true, false) . '>' . esc_html($cat->name) . '</option>';
                                        }
                                    }
                                    ?>
                                </select> <?php echo wc_help_tip(__('Select the category for which the rule would be applied.', 'eh-dynamic-pricing-discounts')); ?></p>
                            <?php
                            //// end category search
                            woocommerce_wp_select(array(
                                'id' => 'check_on',
                                'label' => __('Check for', 'eh-dynamic-pricing-discounts'),
                                'options' => array('Quantity' => 'No of Items',
                                    'Weight' => 'Weight',
                                    'Price' => 'Price',
                                    'TotalQuantity' => 'Total Units'),
                                'value' => !empty($_REQUEST['check_on']) ? $_REQUEST['check_on'] : 'Quantity',
                                'description' => __('The rules can be based on No. of items/Weight/Price/Total Units. "No. of items" denotes the number of distinct products. Whereas "Total Units" denotes the total no. of units of any product.', 'eh-dynamic-pricing-discounts'),
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
                                'description' => __('After Calculation Discount Value Must Not Exceeed This Amount For This Rule', 'eh-dynamic-pricing-discounts'),
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
