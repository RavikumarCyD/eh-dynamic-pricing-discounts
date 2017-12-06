<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

add_action( 'wp_ajax_xa_get_attributes_value_for_taxonomy', 'xa_get_attributes_value_for_taxonomy' );

function xa_get_attributes_value_for_taxonomy()
{   
    $taxonomy=$_POST['taxonomy'];
    $options= xa_get_attributes_values_selectoptions($taxonomy);
    wp_die($options);
}