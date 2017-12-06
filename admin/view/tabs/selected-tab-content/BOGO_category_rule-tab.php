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
    jQuery('#rule_tab label').append('<span style="color:red;padding-left:5px">*<span>');
    jQuery('#discount_type').trigger('change');
    jQuery('#check_on').trigger('change');
    jQuery("[for=max]").html(jQuery("[for=max]").text());
    });
    jQuery(window).load(function(){
    jQuery('.wc-product-search').attr('required', 'required');
    jQuery('.insertpurchased').click(function() {
    var row_no = ((jQuery('#purchased_product_list >tbody >tr').length) + 1);
    var element_id = "purchased_category_id" + row_no;
    jQuery('#purchased_product_list').append('<tr><td name="row_num">		\
            ' + row_no + '	\
            </td>	\
                    <td> \
                         <select id="'+element_id+'" name="'+element_id+'" style="width: 100%;height:30px"  class="wc-enhanced-select"  data-placeholder="<?php esc_attr_e('Any category', 'eh-dynamic-pricing-discounts'); ?>"> \
                            <?php
                            $categories = get_terms('product_cat', 'orderby=name&hide_empty=0');
                            if ($categories) {
                                foreach ($categories as $cat) {
                                    echo '<option value="' . esc_attr($cat->term_id) . '"'  . '>' . esc_html($cat->name) . '</option>';
                                }
                            }
                            ?>
                        </select> \
 </td> \
         <td style="width: 20%;font-size: inherit;"> \
            <select name="purchased_checkon'+row_no+'" style="width: 100%;font-size: small;" > \
                <option value="items">No of Items</option> \
                <option value="units">Total Units</option> \
            </select> \
        </td> \
                                                                 <td style="width: 20%;font-size: inherit;">	\
    <input type="number" name="purchased_quantity'+row_no+'" style="width: 100%;font-size: small;" value="1" />	\
        </td> < /tr>');
                jQuery('#' + element_id).trigger('wc-enhanced-select-init');
        })

        })
                jQuery(window).load(function(){

        jQuery('.insertfree').click(function() {
        var row_no = ((jQuery('#free_product_list >tbody >tr').length) + 1);
        var element_id = "free_product_id" + row_no;
        jQuery('#free_product_list').append('<tr><td name="row_num">		\
            ' + row_no + '	\
            </td>	\
                    <td>	\
<?php if (is_wc_version_gt_eql('2.7')) {
    ?>
    <select  class="wc-product-search"  style="width: 100%;font-size: inherit;" name="'+element_id+'" id="'+ element_id +'" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" > \
    </select> \
<?php } else {
    ?>
            < input type = "hidden" class = "wc-product-search"  style = "width: 100%;font-size: inherit;" name = "'+element_id+'" id = "'+ element_id +'" data - placeholder = "Search for a product" data - action = "woocommerce_json_search_products_and_variations" data - multiple = ""    data - exclude = "<?php
    if (!empty($product_var)) {
        echo implode(',', $product_var);
    }
    ?>" / > \
<?php }
?> </td>	\
       <td style="width: 20%;font-size: inherit;">	\
        <input type="number" name="free_quantity'+row_no+'" style="width: 100%;font-size: small;" value="1" />	\
            </td> < /tr>');
                    jQuery('#' + element_id).trigger('wc-enhanced-select-init');
            })

            })

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

                            show_purchase_product_table();
                            show_free_product_table();
                            ?>
                        </div>
                        <div class="options_group" id="adjustment_tab" style="display: none;">
                            <?php
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

<?php

function show_purchase_product_table() {
    ?>
    <!----------------------------------------------------------------------Purchased Products Table--------------------------------------------------------------->

    <span ><h3 style="text-align:center;padding: 0 50px;"><?php _e('Items in categories need to be purchased','eh-dynamic-pricing-discounts'); ?></h3>        
    </span>
    <div style="text-align:center;padding: 0 15px;">
    <table name="purchased_product_list" id="purchased_product_list" class="widefat" style="">
        <thead style="font-size: inherit;">
            <tr style="font-size: inherit;">
                <th class="xa-table-header" style="font-size: inherit;width:12%"><?php esc_attr_e('Row no', 'eh-dynamic-pricing-discounts'); ?></th>
                <th class="xa-table-header" style="font-size: inherit;"><?php esc_attr_e('Category Name', 'eh-dynamic-pricing-discounts'); ?></th>
                <th class="xa-table-header" style="font-size: inherit;"><?php esc_attr_e('Check on', 'eh-dynamic-pricing-discounts'); ?></th>
                <th class="xa-table-header" style="font-size: inherit;"><?php esc_attr_e('Check min', 'eh-dynamic-pricing-discounts'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_REQUEST['purchased_category_id1']) && !empty($_REQUEST['purchased_category_id1']) && isset($_REQUEST['purchased_quantity1']) && !empty($_REQUEST['purchased_quantity1']) && isset($_REQUEST['edit']) && !empty($_REQUEST['edit'])) {
                echo '<input name="update"  type="hidden" value="' . $_REQUEST['edit'] . '" />';
                $pid_field = 'purchased_category_id1';
                $checkon_field = 'purchased_checkon1';
                $qnty_field = 'purchased_quantity1';
                $product_id_array = array();
                $fieldcount = 1;
                do {
                    ?>
                    <tr>
                        <td name="row_num">
                            <?php echo $fieldcount; ?>
                        </td>
                        <td>
                                <select id="<?php echo $pid_field; ?>" name="<?php echo $pid_field; ?>" style="width: 100%;height:30px"  class="wc-enhanced-select"  data-placeholder="<?php esc_attr_e('Any category', 'eh-dynamic-pricing-discounts'); ?>">
                                    <?php
                                    $category_id = !empty($_REQUEST[$pid_field]) ? $_REQUEST[$pid_field] : '';  //selected product categorie
                                    $categories = get_terms('product_cat', 'orderby=name&hide_empty=0');
                                    if ($categories) {
                                        foreach ($categories as $cat) {
                                            echo '<option value="' . esc_attr($cat->term_id) . '"' . selected($cat->term_id==$category_id, true, false) . '>' . esc_html($cat->name) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                        </td>
                        <?php 
                            if(!empty($_REQUEST[$qnty_field])){
                                $tmp=explode(':',$_REQUEST[$qnty_field]);
                                $qnty=!empty($tmp[0])?$tmp[0]:'0';
                                $checkon=!empty($tmp[1])?$tmp[1]:'items';
                                
                            }
                        ?>
                        <td style="width: 20%;font-size: inherit;">
                            <select name="<?php echo $checkon_field; ?>" style="width: 100%;font-size: small;" >
                                <option value="items"  <?php if($checkon=='items') echo 'selected="selected"'; ?>>No of Items</option>
                                <option value="units" <?php  if($checkon=='units') echo 'selected="selected"'; ?>>Total Units</option>
                            </select>
                        </td>
                        
                        <td style="width: 20%;font-size: inherit;">
                            <input type="number" name="<?php echo $qnty_field; ?>" style="width: 100%;font-size: small;"   value="<?php echo $qnty; ?>"  />
                        </td>
                    </tr>

                    <?php
                    $fieldcount++;
                    $pid_field = 'purchased_category_id' . $fieldcount;
                    $checkon_field = 'purchased_checkon' . $fieldcount;
                    $qnty_field = 'purchased_quantity' . $fieldcount;
                } while (isset($_REQUEST[$pid_field]) && !empty($_REQUEST[$pid_field]) && isset($_REQUEST[$qnty_field]) && !empty($_REQUEST[$qnty_field]));
            } else {
                ?><tr>
                    <td name="row_num">
                        1
                    </td>
                    <td>
                        <select id="category_id" name="purchased_category_id1" style="width: 100%;height:30px"  class="wc-enhanced-select"  data-placeholder="<?php esc_attr_e('Any category', 'eh-dynamic-pricing-discounts'); ?>">
                            <?php
                            $categories = get_terms('product_cat', 'orderby=name&hide_empty=0');
                            if ($categories) {
                                foreach ($categories as $cat) {
                                    echo '<option value="' . esc_attr($cat->term_id) . '"'  . '>' . esc_html($cat->name) . '</option>';
                                }
                            }
                            ?>
                        </select>

                    </td>
                    <td style="width: 20%;font-size: inherit;">
                        <select name="purchased_checkon1" style="width: 100%;font-size: small;" >
                            <option value="items">No of Items</option>
                            <option value="units">Total Units</option>
                        </select>
                    </td>
                    <td style="width: 20%;font-size: inherit;">
                        <input type="number" name="purchased_quantity1" style="width: 100%;font-size: small;"   value="1"  />
                    </td>
                </tr>
                <?php
            }
            ?>


        </tbody>
        <tfoot>
            <tr><td colspan=4><span style="text-align:center;font-size: 12px;display: block;">
                           "No. of items" denotes the number of distinct products. Whereas "Total Units" denotes the total no. of units of any product. 
                            </span></td></tr>
            <tr>
                <td colspan=4>
                    <a  class="button insert insertpurchased" name="insertbtn" id="insertbtn" ><?php esc_attr_e('Add Category', 'eh-dynamic-pricing-discounts'); ?></a>
                    <a  class="button delete deletepurchased" name="deletebtn" id="deletebtn" ><?php esc_attr_e('Delete Category', 'eh-dynamic-pricing-discounts'); ?></a></td>
            </tr>
        </tfoot>
    </table>
    </div>
    <!----------------------------------------------------------------------End of Free Products Table--------------------------------------------------------------->

    <?php
}

function show_free_product_table() {
    ?>
    <!--------------------------------------------------------------Table For Free Products--------------------------------------------------------------------------->
    <span ><h3 style="text-align:center;padding: 0 50px;"><?php _e('Product to be set as free','eh-dynamic-pricing-discounts'); ?></h3>
        <span style="padding: 0 15px;font-size: 12px;">
            <?php _e('Enter the products which will be set as free if all above products in respective quantities are purchased','eh-dynamic-pricing-discounts'); ?>
        </span>            
    </span>
    <div style="text-align:center;padding: 0 15px;">
    <table name="free_product_list" id="free_product_list" class="widefat" style="font-size: inherit;">
        <thead style="font-size: inherit;">
            <tr style="font-size: inherit;">
                <th class="xa-table-header" style="font-size: inherit;width:12%"><?php esc_attr_e('Row no', 'eh-dynamic-pricing-discounts'); ?></th>
                <th class="xa-table-header" style="font-size: inherit;"><?php esc_attr_e('Product Name', 'eh-dynamic-pricing-discounts'); ?></th>
                <th class="xa-table-header" style="font-size: inherit;"><?php esc_attr_e('Quantity', 'eh-dynamic-pricing-discounts'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_REQUEST['free_product_id1']) && !empty($_REQUEST['free_product_id1']) && isset($_REQUEST['free_quantity1']) && !empty($_REQUEST['free_quantity1']) && isset($_REQUEST['edit']) && !empty($_REQUEST['edit'])) {
                echo '<input name="update"  type="hidden" value="' . $_REQUEST['edit'] . '" />';
                $pid_field = 'free_product_id1';
                $qnty_field = 'free_quantity1';
                $product_id_array = array();
                $fieldcount = 1;
                do {
                    ?>
                    <tr>
                        <td name="row_num">
                            <?php echo $fieldcount; ?>
                        </td>
                        <td>
                            <?php if (is_wc_version_gt_eql('2.7')) {
                                ?>
                                <select class="wc-product-search" data-multiple="false" style="width: 100%;" name="<?php echo $pid_field; ?>" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'eh-dynamic-pricing-discounts'); ?>" data-action="woocommerce_json_search_products_and_variations" ><?php
                                    $product_id = $_REQUEST[$pid_field];
                                    $product = wc_get_product($product_id);
                                    echo "<option value=" . $product_id . " selected> " . $product->get_formatted_name() . "</option>";
                                    ?>  </select>

                                <?php
                            } else {
                                ?>
                                <input type="hidden" class="wc-product-search" data-multiple="false" style="width: 100%;" name="<?php echo $pid_field; ?>" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'eh-dynamic-pricing-discounts'); ?>" data-action="woocommerce_json_search_products_and_variations" data-selected="<?php
                                $product_id = $_REQUEST[$pid_field];
                                $product = wc_get_product($product_id);
                                $tmp=$product->get_formatted_name();
                                $tmp = explode("<",$tmp );
                                echo $tmp[0];
                                ?>" value="<?php echo $product_id; ?>"     data-exclude="<?php
                                       if (!empty($product_var)) {
                                           echo implode(',', $product_var);
                                       }
                                       ?>"  />
                                   <?php } ?>


                        </td>

                        <td style="width: 20%;font-size: inherit;">
                            <input type="number" name="<?php echo $qnty_field; ?>" style="width: 100%;font-size: small;"   value="<?php echo $_REQUEST[$qnty_field]; ?>"  />
                        </td>
                    </tr>

                    <?php
                    $fieldcount++;
                    $pid_field = 'free_product_id' . $fieldcount;
                    $qnty_field = 'free_quantity' . $fieldcount;
                } while (isset($_REQUEST[$pid_field]) && !empty($_REQUEST[$pid_field]) && isset($_REQUEST[$qnty_field]) && !empty($_REQUEST[$qnty_field]));
            } else {
                ?><tr>
                    <td name="row_num">
                        1
                    </td>
                    <td>
                        <?php if (is_wc_version_gt_eql('2.7')) {
                            ?>
                            <select class="wc-product-search" data-multiple="false" style="width: 100%;" name="free_product_id1" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'eh-dynamic-pricing-discounts'); ?>" data-action="woocommerce_json_search_products_and_variations" ></select>

                            <?php
                        } else {
                            ?>
                            <input type="hidden" class="wc-product-search cls" data-multiple="false" style="width: 100%;font-size: inherit;" name="free_product_id1" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'eh-dynamic-pricing-discounts'); ?>" data-action="woocommerce_json_search_products_and_variations"   data-exclude="<?php
                            if (!empty($product_var)) {
                                echo implode(',', $product_var);
                            }
                            ?>"   />
                               <?php } ?>

                    </td>

                    <td style="width: 20%;font-size: inherit;">
                        <input type="number" name="free_quantity1" style="width: 100%;font-size: small;"   value="1"  />
                    </td>
                </tr>
                <?php
            }
            ?>


        </tbody>
        <tfoot>
            <tr>
                <td colspan=3>
                    <a  class="button insert insertfree" name="freeinsertbtn" id="freeinsertbtn" ><?php esc_attr_e('Add Product', 'eh-dynamic-pricing-discounts'); ?></a>
                    <a  class="button delete deletefree" name="freedeletebtn" id="freedeletebtn" ><?php esc_attr_e('Delete Product', 'eh-dynamic-pricing-discounts'); ?></a></td>
            </tr>
        </tfoot>
    </table>
    </div>    
    <!----------------------------------------------------------------------End of Free Products Table--------------------------------------------------------------->

    <?php
}
