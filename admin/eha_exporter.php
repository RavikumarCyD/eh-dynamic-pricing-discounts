<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
add_action('admin_init', 'eha_export_rules');

function eha_export_rules() {
    if (isset($_POST['eha-export-json'])) {
        $rules = get_option("xa_dp_rules", array());
        $tab_c = $_POST['tab'];
        if(!isset($rules[$tab_c])) wp_die('No Rules Present'.' <a href="'.admin_url('admin.php?page=dynamic-pricing-main-page&tab=import_export').'"> Go Back</a>');
        if(empty($rules[$tab_c])){
            wp_die('Sorry No Rules Found Under This Selection.'.' <a href="'.admin_url('admin.php?page=dynamic-pricing-main-page&tab=import_export').'"> Go Back</a>');  
        }
        
        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $tab_c . '-export-' . date('d-M-Y') . '.json');
        header("Expires: 0");
        $data=array('type'=> $tab_c , 'rules'=>$rules[$tab_c]);
        echo json_encode($data);
        exit;
    }elseif (isset($_POST['eha-export-csv'])) {
        $rules = get_option("xa_dp_rules", array());
        $tab_c = $_POST['tab'];
        if(!isset($rules[$tab_c])) wp_die('No Rules Present'.' <a href="'.admin_url('admin.php?page=dynamic-pricing-main-page&tab=import_export').'"> Go Back</a>');
        if(empty($rules[$tab_c])){
            wp_die('Sorry No Rules Found Under This Selection.'.' <a href="'.admin_url('admin.php?page=dynamic-pricing-main-page&tab=import_export').'"> Go Back</a>');  
        }
        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $tab_c . '-export-' . date('d-M-Y') . '.csv');
        header("Expires: 0");
        $output_file_name=$tab_c . '-export-' . date('m-d-Y') . '.csv';
        foreach(current($rules[$tab_c]) as $colname=>$colval)
        {
            $line[]=$colname;
        }
        $lines=implode(',',$line);
        echo $lines."\n";
        foreach($rules[$tab_c] as $row){ 
            $line=array();
            foreach($row as $k=>$v)
            {
                if(is_array($v) || is_object($v))
                {
                    $v=(array)$v;
                    $tmp='';
                    foreach($v as $k2=>$v2){
                        $tmp.=(empty($tmp)?"   ":" | ")."$k2=>$v2";
                    }
                    $line[]="[$tmp]";
                }else
                {
                    $v= str_replace(',', '&comma', $v);
                    $line[]=$v;
                }
            }
            $lines=implode(',',$line);
            echo $lines."\n";
        }
        
        exit;
    }
}
