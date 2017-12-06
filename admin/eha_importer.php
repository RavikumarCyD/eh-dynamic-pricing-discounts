<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
add_action('admin_init', 'eha_import_rules');

function eha_import_rules() {
    $rules_array = get_option("xa_dp_rules", array());
    if (isset($_POST['import_tab']) && !empty($_POST['import_tab']) && !empty($_FILES['import_file'])) {
        $tab = $_POST['import_tab'];
        $mode = !empty($_POST['import_mode'])?$_POST['import_mode']:'';
        $tmp = explode('.', $_FILES['import_file']['name']);
        $extension = end($tmp);
        if ($extension == 'csv') {
        if(strpos( $_FILES['import_file']['name'] ,$tab) !== false)
        {
            $import_file = $_FILES['import_file']['tmp_name'];
            if (empty($import_file)) {
                wp_die(__('Please upload a file to import'));
            }
            $data =file_get_contents($import_file);
            $header=array();
            if ( ( $handle = fopen( $import_file, "r" ) ) !== FALSE ) {
                $header = fgetcsv( $handle, 0, ",");
            }
            $rules=get_option('xa_dp_rules', array());
            while ( ( $row = fgetcsv( $handle, 0, "," ) ) !== FALSE )
            {
                $rule=array();
                if($tab=="product_rules")
                {
                    list($offer_name,$rule_on,$product_id,$category_id,$check_on,$min,$max,$discount_type,$value,$max_discount,$allow_roles,$from_date,$to_date,$adjustment,$email_ids,$prev_order_count,$prev_order_total_amt,$repeat_rule) = $row;                
                    $cols="offer_name,rule_on,product_id,category_id,check_on,min,max,discount_type,value,max_discount,allow_roles,from_date,to_date,adjustment,email_ids,prev_order_count,prev_order_total_amt,repeat_rule";
                }elseif($tab=="combinational_rules")
                {
                    list($offer_name,$product_id,$discount_type,$value,$max_discount,$allow_roles,$from_date,$to_date,$adjustment,$email_ids,$prev_order_count,$prev_order_total_amt,$discount_on_product_id) = $row;                
                    $cols="offer_name,product_id,discount_type,value,max_discount,allow_roles,from_date,to_date,adjustment,email_ids,prev_order_count,prev_order_total_amt,discount_on_product_id";
                }elseif($tab=="cat_combinational_rules")
                {
                    list($offer_name,$cat_id,$discount_type,$value,$max_discount,$allow_roles,$from_date,$to_date,$adjustment,$email_ids,$prev_order_count,$prev_order_total_amt) = $row;                
                    $cols="offer_name,cat_id,discount_type,value,max_discount,allow_roles,from_date,to_date,adjustment,email_ids,prev_order_count,prev_order_total_amt";
                }elseif($tab=="category_rules")
                {
                    list($offer_name,$category_id,$check_on,$min,$max,$discount_type,$value,$max_discount,$allow_roles,$from_date,$to_date,$adjustment,$email_ids,$prev_order_count,$prev_order_total_amt) = $row;                
                    $cols="offer_name,category_id,check_on,min,max,discount_type,value,max_discount,allow_roles,from_date,to_date,adjustment,email_ids,prev_order_count,prev_order_total_amt";
                }elseif($tab=="cart_rules")
                {
                    list($offer_name,$check_on,$min,$max,$discount_type,$value,$max_discount,$allow_roles,$from_date,$to_date,$adjustment,$email_ids,$prev_order_count,$prev_order_total_amt) = $row;                
                    $cols="offer_name,check_on,min,max,discount_type,value,max_discount,allow_roles,from_date,to_date,adjustment,email_ids,prev_order_count,prev_order_total_amt";
                }elseif($tab=="buy_get_free_rules")
                {
                    list($offer_name,$purchased_product_id,$free_product_id,$allow_roles,$from_date,$to_date,$adjustment,$email_ids,$prev_order_count,$prev_order_total_amt) = $row;                
                    $cols="offer_name,purchased_product_id,free_product_id,allow_roles,from_date,to_date,adjustment,email_ids,prev_order_count,prev_order_total_amt";
                }elseif($tab=="BOGO_category_rules")
                {
                    list($offer_name,$purchased_category_id,$free_product_id,$allow_roles,$from_date,$to_date,$adjustment,$email_ids,$prev_order_count,$prev_order_total_amt) = $row;                
                    $cols="offer_name,purchased_category_id,free_product_id,allow_roles,from_date,to_date,adjustment,email_ids,prev_order_count,prev_order_total_amt";
                }
                $cols=explode(",",$cols);
                foreach($cols as $col)
                {   
                    $tmp=$$col;
                    if($tmp=='[]')
                    {
                        $tmp=array();
                    }
                    else if(!empty($tmp) && $tmp[0]=='[')
                    {   
                        
                        $tmp1= str_replace("[", "", $tmp);
                        $tmp1= str_replace("]", "", $tmp1);
                        $tmp1= explode(' | ',$tmp1);
                        $tmp=array();
                        foreach($tmp1 as $keyval)
                        {   
                            $tmp2 =explode("=>",$keyval);
                            $key=!empty($tmp2[0])?$tmp2[0]:'';
                            $val=!empty($tmp2[1])?$tmp2[1]:'';
                            $key=trim($key);
                            $val=trim($val);
                            $tmp[$key]=$val;
                        }
                    }
                    $rule[$col]=$tmp;
                }
                if(empty($rules[$tab]))
                {
                    $rules[$tab][1]=$rule;
                }else
                {
                    $rules[$tab][]=$rule;
                }
            }
            update_option('xa_dp_rules', $rules);
            function eha_admin_notice_import_success() {
                ?>
                <div class="notice notice-success is-dismissible" style="">
                    <p><?php _e('Imported Successfully!', 'eh-dynamic-pricing-discounts'); ?></p>
                </div>
                <?php
            }
            add_action('admin_notices', 'eha_admin_notice_import_success');
        }else {
             wp_die('You can not import '.$_FILES['import_file']['name'].' into '.$tab.' <a href="'.admin_url('admin.php?page=dynamic-pricing-main-page&tab=import_export').'"> Go Back</a>');
        }
        } elseif($extension == 'json')  {
            $import_file = $_FILES['import_file']['tmp_name'];
            if (empty($import_file)) {
                wp_die(__('Please upload a file to import'));
            }
            $data = (array) json_decode(file_get_contents($import_file),true);
            if (!empty($data['type']) && $data['type'] !== $tab) {
                wp_die('You can not import '.$data['type'].' into '.$tab.' <a href="'.admin_url('admin.php?page=dynamic-pricing-main-page&tab=import_export').'"> Go Back</a>');
            }
            $settings = isset($data['rules'])?$data['rules']:null;
            if (!empty($settings)) {
                foreach ($settings as $key => $row) {
                    if (!isset($rules_array[$tab][$key]) || $mode == 'overwrite') {
                        $rules_array[$tab][$key] = (array) $row;
                    } elseif ($mode == 'newindex') {
                        $rules_array[$tab][] = (array) $row;
                    } else {  //Skip
                    }
                }
            }

            update_option('xa_dp_rules', $rules_array);

            function eha_admin_notice_import_success() {
                ?>
                <div class="notice notice-success is-dismissible" style="">
                    <p><?php _e('Imported Successfully!', 'eh-dynamic-pricing-discounts'); ?></p>
                </div>
                <?php
            }

            add_action('admin_notices', 'eha_admin_notice_import_success');
        }else
        {
            wp_die(__('Please upload a valid .json  or .csv file'));
        }
    }
}
