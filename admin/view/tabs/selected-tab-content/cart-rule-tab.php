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
                        <li class="advance_restricion_options " style="display: block;">
                            <a class="xa_link" onclick="select(this, '#advance_restriction_tab')">
                                <span><?php _e('Advance Restrictions','eh-dynamic-pricing-discounts'); ?></span>
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
                            /* RavikumarMG 4/12/2017 
                             * Adding options for limiting minimum and maximum stock quantities
                             * START
                             */
                            woocommerce_wp_text_input(array(
                                'id' => 'minimum_stock_limit',
                                'label' => __('Minimum Stock Limit', 'eh-dynamic-pricing-discounts'),
                                'description' => __('Minimum product stock required', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'custom_attributes' => array('step'=>'any'),
                                'value' => !empty($_REQUEST['minimum_stock_limit']) ? $_REQUEST['minimum_stock_limit'] : ''
                            ));
                            woocommerce_wp_text_input(array(
                                'id' => 'maximum_stock_limit',
                                'label' => __('Maximum Stock Limit', 'eh-dynamic-pricing-discounts'),
                                'description' => __('Maximum product stock required', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'custom_attributes' => array('step'=>'any'),
                                'value' => !empty($_REQUEST['maximum_stock_limit']) ? $_REQUEST['maximum_stock_limit'] : ''
                            ));
                            /*END*/
                            
                            ?>
                            </div>
                            <div class="options_group" id="advance_restriction_tab" style="display: none;">
                            <style>
                                .at-row{
                                      line-height: 50px;
                                      background: white;
                                      text-align: center;
                                }
                                .at-cell{
                                    padding:3px 15px;
                                }
                            </style>
                            <script>
                            jQuery(document).ready(function(){
                                var ajax_url=<?php echo "'".admin_url( 'admin-ajax.php' )."'"; ?>;
                                jQuery('.add_attribute').on('click',function(){
                                    jQuery('.at-row.emptyrow').remove() ;
                                    $table=jQuery('#attributes_list').find('table>tbody');
                                    $row='<?php global $at_taxonomy_options,$at_taxonomy_list; 
                                                xa_set_global_product_attributes();
                                                $options=xa_get_attributes_values_selectoptions(key($at_taxonomy_list));
                                                $row=  '<tr class="at-row">
                                                    <td class="at-cell" style="width:5%;">
                                                        <input type="checkbox" name="at_cb[]" />
                                                    </td>
                                                    <td class="at-cell">
                                                        <select  class="select2 attribute_ajax" style="width:100%;" name="at_taxonomy[]" >
                                                            '.$at_taxonomy_options.'
                                                        </select>
                                                    </td>
                                                    <td class="at-cell">
                                                        <select class="select2 attribute_value" style="width:100%;" name="at_val[]" >
                                                         '.$options.'
                                                        </select>
                                                    </td>                                        
                                                </tr>';
                                                echo trim(preg_replace('/\s\s+/', ' ', $row));
                                                ?>';
                                    $table.append($row);
                                    jQuery('#attributes_list').scrollTop(jQuery('.attribute_ajax').size() * 60);
                                }); 
                                jQuery('.delete_attribute').on('click',function(){
                                    $table=jQuery('#attributes_list').find('table>tbody')
                                    $selected_rows=$table.find('input[type="checkbox"]:checked');
                                    $selected_rows.each(function(index,$ele){
                                        $ele.closest('tr').remove();
                                    });
                                });
                                jQuery('#attributes_list').on('change','.attribute_ajax',function(e){ 
                                    let taxonomy_slug=jQuery(this).val();
                                    let data={'action':'xa_get_attributes_value_for_taxonomy','taxonomy':taxonomy_slug};
                                    jQuery.blockUI({message: ""}); 
                                    let select_attribute=jQuery(this).parent().next('td').find('select');
                                    
                                    jQuery.post(ajax_url,data,function(responce){
                                                    select_attribute.html(responce);
                                                    jQuery.unblockUI();
                                                });
                                });
                            });
                            </script>
                            <div style="clear:both;width: 63%;">
                                <h3 style="margin:15px;float:left;">Based on Product Attribute</h3> 
                                <select name='attributes_mode' style="float:right;margin: 5px;">
                                    <?php $tmp=!empty($_REQUEST['attributes_mode'])?$_REQUEST['attributes_mode']:'or'; ?>
                                    <option value="or" <?php selected($tmp,'or')?>>Match any one attribute</option>
                                    <option value="and" <?php selected($tmp,'and')?>>Match all attributes</option>
                                </select>
                            </div>
                            <div id="attributes_list" name="attributes_list" class="form-field" style="clear:both;overflow: auto;width: 63%; height: 150px; background: #f9f6f6; margin: 12px; box-shadow: 8px 8px 7px rgba(136, 136, 136, 0.22);">
                                <table style="width:100%">
                                    <thead>
                                    <tr style="background: cadetblue; color: white;">
                                    <th style="width:46px"></th>
                                    <th>Attribute Name</th>
                                    <th>Attribute Value</th>
                                    </tr>
                                    </thead>
                                    <tbody>        
                                    <?php
                                    xa_set_global_product_attributes();
                                    global $at_taxonomy_options,$at_value_options;
                                    if(!empty($_REQUEST['attributes']) && !empty($_REQUEST['attributes']['at_taxonomy']))
                                    {   
                                        foreach($_REQUEST['attributes']['at_taxonomy'] as $key=>$at_taxonomy)
                                        {
                                            $at_val=!empty($_REQUEST['attributes']['at_val'][$key])?$_REQUEST['attributes']['at_val'][$key]:'';                                            
                                            ?>
                                                <tr class="at-row">
                                                    <td class="at-cell" style="width:5%;">
                                                        <input type="checkbox" name="at_cb[]" />
                                                    </td>
                                                    <td class="at-cell">
                                                        <select  class="select2 attribute_ajax" style="width:100%;" name="at_taxonomy[]" >
                                                            <?php echo xa_get_taxonomy_selectoptions($at_taxonomy); ?>
                                                        </select>
                                                    </td>
                                                    <td class="at-cell">
                                                        <select class="select2 attribute_value" style="width:100%;" name="at_val[]" >
                                                            <?php echo xa_get_attributes_values_selectoptions($at_taxonomy,$at_val);?>;
                                                        </select>
                                                    </td>                                        
                                                </tr>
                                            <?php
                                        }
                                    }else
                                    {
                                        ?>
                                        <tr class="at-row emptyrow" style="line-height: 20px;">
                                            <td class="at-cell" style="width:5%;">
                                               
                                            </td>
                                            <td class="at-cell">
                                                 <span>No records found !!</span>
                                            </td>
                                            <td class="at-cell">
                                            </td>                                        
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>             
                            </div>
                            <?php  
                            ?>
                            <div class="btn-nav" style="width:100%;margin:15px;">
                                <a   class="button add_attribute">Add New Attribute</a> 
                                <a   class="button delete_attribute">Delete Selected Attribute</a>
                            </div>
                            
                            <hr>
                            <?php  /////////////Discount based on payment methods ?>
                            <div style="clear:both;width: 63%;">
                                <h3 style="margin:15px;float:left;">Based on Payment Methods</h3> 
                            </div>
                            <?php  
                            $payment_gateway_obj = new WC_Payment_Gateways();
                            $available_methods=$payment_gateway_obj->get_available_payment_gateways();
                            $get_title=function($obj){
                                                        return $obj->get_method_title();
                                                };
                            $available_methods=array_map($get_title,$available_methods);
                            ?>
                            <div id="allowed_payment_methods" name="allowed_payment_methods" style="clear:both;padding-left:10px;width: 70%;">
<?php
                            xa_woocommerce_wp_select_multiple(array(
                                'id' => 'allowed_payment_methods[]',
                                'label' => __('Allowed Payment Methods', 'eh-dynamic-pricing-discounts'),
                                'options' => $available_methods,
                                'value' => !empty($_REQUEST['allowed_payment_methods']) ? $_REQUEST['allowed_payment_methods'] : array(),
                                'description' => __('', 'eh-dynamic-pricing-discounts'),
                                'desc_tip' => true,
                                'custom_attributes' => array('multiple' => ''),
                                'class' =>'payment_method_select',
                                'style' => 'width:90%;'
                            ));
                            $shipping_obj = new WC_Shipping();
                            $available_shipping_methods=$shipping_obj->get_shipping_methods();
                            $get_title=function($obj){
                                                        return $obj->get_method_title();
                                                };
                            $available_shipping_methods=array_map($get_title,$available_shipping_methods);
?>
                            </div>
                            <div id="allowed_shipping_methods" name="allowed_shipping_methods" style="clear:both;padding-left:10px;width: 70%;">
<?php
                            xa_woocommerce_wp_select_multiple(array(
                                'id' => 'allowed_shipping_methods[]',
                                'label' => __('Allowed Shipping Methods', 'eh-dynamic-pricing-discounts'),
                                'options' => $available_shipping_methods,
                                'value' => !empty($_REQUEST['allowed_shipping_methods']) ? $_REQUEST['allowed_shipping_methods'] : array(),
                                'description' => __('', 'eh-dynamic-pricing-discounts'),
                                'desc_tip' => true,
                                'custom_attributes' => array('multiple' => ''),
                                'class' =>'payment_method_select',
                                'style' => 'width:90%;'
                            ));

?>
                            </div>
                            
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>
