<?php
if (isset($_REQUEST['edit']) && !empty($_REQUEST['edit'])) {
    echo '<input type="hidden" name="update" value="' . $_REQUEST['edit'] . '" >';
}
?>   
<script>
    jQuery(document).ready(function () {

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
        let row_no = ((jQuery('#product_list >tbody >tr').length) + 1);
        jQuery('.insert').click(function () {
            var row_no = ((jQuery('#product_list >tbody >tr').length) + 1);
            var element_id = "cat_id" + row_no;
            jQuery('#product_list').append('<tr><td name="row_num">' + row_no + ' </td><td style="font-size: inherit;width:40%;max-width: 200px;"><select id="' + element_id + '" class="categorycombo"   style="width:100%;"  name="' + element_id + '"><?php
$product_category = get_terms('product_cat', array('fields' => 'id=>name', 'hide_empty' => false, 'orderby' => 'title', 'order' => 'ASC',));
if ($product_category)
    foreach ($product_category as $product_id => $product_name) :
        echo '<option value="' . $product_id . '"';
        echo '>' . esc_js($product_name) . '</option>';
    endforeach;
?></select></td><td style="width: 20%;font-size: inherit;"><input type="number" name="quantity' + row_no + '" style="width: 100%;font-size: small;" value=1   /></td></tr>');
            jQuery('#' + element_id).trigger('wc-enhanced-select-init');

        });
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
                                'value' => !empty($_REQUEST['offer_name'])?$_REQUEST['offer_name']:'',
                                'custom_attributes' => array( 'required' => 'required' )
                            ));


                            generate_product_table_html();
                            woocommerce_wp_select(array(
                                'id' => 'discount_type',
                                'label' => __('Discount type', 'eh-dynamic-pricing-discounts'),
                                'options' => array('Percent Discount' => 'Percent Discount',
                                    'Flat Discount' => 'Flat Discount',
                                    'Fixed Price' => 'Fixed Price',),
                                'value' => !empty($_REQUEST['discount_type'])?$_REQUEST['discount_type']:'Percent Discount',
                                'description' => __('Three types of discounts can be applied – "Percentage Discount/Flat Discount/Fixed Price”', 'eh-dynamic-pricing-discounts'),
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
                                'value' => !empty($_REQUEST['value'])?$_REQUEST['value']:'',
                                'custom_attributes' => array( 'required' => 'required',"step"=>"any" )
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
                                'value' => !empty($_REQUEST['max_discount'])?$_REQUEST['max_discount']:'',
                                'custom_attributes' => array("step"=>"any")
                            ));
                            woocommerce_wp_text_input(array(
                                'id' => 'adjustment',
                                'label' => __('Adjustment amount', 'eh-dynamic-pricing-discounts'),
                                'description' => __('Adjust final discount amount by this amount', 'eh-dynamic-pricing-discounts'),
                                'type' => 'number',
                                'desc_tip' => true,
                                'class' => 'short',
                                'value' => !empty($_REQUEST['adjustment'])?$_REQUEST['adjustment']:'',
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
                                'value' => esc_attr(!empty($_REQUEST['from_date'])?$_REQUEST['from_date']:''),
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
                                'value' => esc_attr(!empty($_REQUEST['to_date'])?$_REQUEST['to_date']:''),
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
                                'value' => !empty($_REQUEST['email_ids'])?$_REQUEST['email_ids']:''
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
                                'value' => !empty($_REQUEST['prev_order_count'])?$_REQUEST['prev_order_count']:''
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
                                'value' => !empty($_REQUEST['prev_order_total_amt'])?$_REQUEST['prev_order_total_amt']:''
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
<?php

function generate_product_table_html() {
    ?>

    <p class="form-field offer_name_field ">
        <label for="offer_name">Combination</label>
    </p>
    <div style="text-align: center; padding: 0 15px;">
    <table name="product_list" id="product_list" class="widefat shipping_pro_boxes" style="font-size: inherit;">
        <thead style="font-size: inherit;">
            <tr style="font-size: inherit;">
                <th class="xa-table-header" style="font-size: inherit;width:10%"><?php esc_attr_e('#Row no', 'eh-dynamic-pricing-discounts'); ?></th>
                <th class="xa-table-header" style="font-size: inherit;"><?php esc_attr_e('Category Name', 'eh-dynamic-pricing-discounts'); ?></th>
                <th class="xa-table-header" style="font-size: inherit;"><?php esc_attr_e('Quantity', 'eh-dynamic-pricing-discounts'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( !empty($_REQUEST['cat_id1']) &&  !empty($_REQUEST['quantity1']) && !empty($_REQUEST['edit'])) {
                echo '<input name="update"  type="hidden" value="' . $_REQUEST['edit'] . '" />';
                $pid_field = 'cat_id1';
                $qnty_field = 'quantity1';
                $product_id_array = array();
                $fieldcount = 1;
                do {
                    ?>
                    <tr>
                        <td name="row_num">
                            <?php echo $fieldcount; ?>
                        </td>
                        <td style="font-size: inherit;width:40%;max-width: 200px;">

                            <select id="<?php echo $pid_field; ?>" class="categorycombo"   style="width:100%;"  name='<?php echo $pid_field; ?>'>

                                <?php
                                $selected_categories = array();
                                if (isset($_REQUEST[$pid_field])) {
                                    $selected_categories = array($_REQUEST[$pid_field]);
                                }

                                $product_category = get_terms('product_cat', array('fields' => 'id=>name', 'hide_empty' => false, 'orderby' => 'title', 'order' => 'ASC',));

                                if ($product_category)
                                    foreach ($product_category as $product_id => $product_name) :
                                        echo '<option value="' . $product_id . '"';
                                        if (!empty($selected_categories) && in_array($product_id, $selected_categories))
                                            echo ' selected="selected"';
                                        echo '>' . esc_js($product_name) . '</option>';
                                    endforeach;
                                ?>
                            </select>
                        </td>

                        <td style="width: 20%;font-size: inherit;">
                            <input type="number" name="<?php echo $qnty_field; ?>" style="width: 100%;font-size: small;"   value="<?php echo $_REQUEST[$qnty_field]; ?>"  />
                        </td>
                    </tr>

                    <?php
                    $fieldcount++;
                    $pid_field = 'cat_id' . $fieldcount;
                    $qnty_field = 'quantity' . $fieldcount;
                }while (isset($_REQUEST[$pid_field]) && !empty($_REQUEST[$pid_field]) && isset($_REQUEST[$qnty_field]) && !empty($_REQUEST[$qnty_field]));
            }
            else {
                ?><tr>
                    <td name="row_num">
                        1
                    </td>
                    <td style="font-size: inherit;width:40%;max-width: 200px;">

                        <select id="product_category_combo" class="categorycombo"   style="width:100%;"  name='cat_id1'>

                            <?php
                            $selected_categories = array();
                            if (isset($_REQUEST['cat_id1'])) {
                                $selected_categories = array($_REQUEST['cat_id1']);
                            }

                            $product_category = get_terms('product_cat', array('fields' => 'id=>name', 'hide_empty' => false, 'orderby' => 'title', 'order' => 'ASC',));

                            if ($product_category)
                                foreach ($product_category as $cat_id => $product_name) :
                                    echo '<option value="' . $cat_id . '"';
                                    if (!empty($selected_categories) && in_array($cat_id, $selected_categories))
                                        echo ' selected="selected"';
                                    echo '>' . esc_js($product_name) . '</option>';
                                endforeach;
                            ?>
                        </select>
                    </td>

                    <td style="width: 20%;font-size: inherit;">
                        <input type="number" name="quantity1" style="width: 100%;font-size: small;"   value="1"  />
                    </td>
                </tr>
                <?php
            }
            ?>


        </tbody>
        <tfoot>
            <tr>
                <td colspan=3>
                    <a  class="button insert" name="insertbtn" id="insertbtn" ><?php _e('Add Category', 'eh-dynamic-pricing-discounts'); ?></a>
                    <a  class="button delete" name="deletebtn" id="deletebtn" ><?php _e('Delete Category', 'eh-dynamic-pricing-discounts'); ?></a></td>
            </tr>
        </tfoot>
    </table>
</div>
    <?php
}
?>