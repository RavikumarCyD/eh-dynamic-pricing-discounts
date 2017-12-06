<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?> 

<div class="wrap">

    <div class="metabox-holder">
        <div class="postbox" id="pb1" name="pb1" hidden><form method="post" enctype="multipart/form-data"></form></div>
        <div class="postbox" id="pb2" name="pb2">
            <h3><span style="    font-size: 1.3em;"><?php _e('Export Rules'); ?></span></h3>
            <div class="inside">
                <p><?php _e('Export the rules to a JSON or CSV format (.json,.csv) file'); ?></p>
                <form method="post" enctype="multipart/form-data">
                    <p>
                        <Select name="tab" id="tab">
                            <option value="product_rules">Product Rules</option>
                            <option value="combinational_rules">Combinational Rules</option>
                            <option value="cat_combinational_rules">Category Combinational Rules</option>
                            <option value="category_rules">Category Rules</option>
                            <option value="cart_rules">Cart Rules</option>
                            <option value="buy_get_free_rules">Buy And Get Free (Offers)</option>
                            <option value="BOGO_category_rules">BOGO Category Rules</option>
                        </Select>
                        <?php wp_nonce_field('eha_export_nonce', 'eha_export_nonce'); ?>
                        <?php submit_button(__('Export to JSON'), 'secondary', 'eha-export-json', false); ?>
                        <?php submit_button(__('Export to CSV'), 'secondary', 'eha-export-csv', false); ?>
                    </p>
                </form>
            </div>
        </div>
    </div><!-- .metabox-holder -->
    <div class="postbox" id="pb3" name="pb3">
        <h3><span style="margin:10px;"><?php _e('JSON Importer'); ?></span></h3>
        <div class="inside">
            <p><?php _e('Import the rules from a .json file.'); ?></p>
            <form method="post" enctype="multipart/form-data" id="importfrm" name="importfrm">
                <p>
                    <Select name="import_tab" id="tab">
                        <option value="product_rules">Product Rules</option>
                        <option value="combinational_rules">Combinational Rules</option>
                        <option value="cat_combinational_rules">Category Combinational Rules</option>
                        <option value="category_rules">Category Rules</option>
                        <option value="cart_rules">Cart Rules</option>
                        <option value="buy_get_free_rules">Buy And Get Free (Offers)</option>
                        <option value="BOGO_category_rules">BOGO Category Rules</option>
                    </Select>
                     <Select name="import_mode" >
                        <option value="overwrite">Overwrite if rule number exists</option>
                        <option value="newindex">Create Rule with new rule number</option>
                        <option value="skip">Skip specific rule if rule number exists</option>
                    </Select>                   
                    <input type="file" name="import_file" accept=".json"/>
                </p>
                <p>
                    <input type="hidden" name="pwsix_action" value="import_settings" />

                    <?php wp_nonce_field('eha_import_export_nonce', 'eha_import_export_nonce'); ?>
                    <?php submit_button(__('Import'), 'secondary', 'eha-import', false); ?>
                </p>
            </form>
        </div><!-- .inside -->
    </div><!-- .postbox -->

    <div class="postbox" id="pb4" name="pb4">
        <h3><span style="margin:10px;"><?php _e('CSV Importer'); ?></span></h3>
        <div class="inside">
            <p><?php _e('Import the rules from a .csv file.'); ?></p>
            <form method="post" enctype="multipart/form-data" id="importfrm" name="importfrm">
                <p>
                    <Select name="import_tab" id="tab">
                        <option value="product_rules">Product Rules</option>
                        <option value="combinational_rules">Combinational Rules</option>
                        <option value="cat_combinational_rules">Category Combinational Rules</option>
                        <option value="category_rules">Category Rules</option>
                        <option value="cart_rules">Cart Rules</option>
                        <option value="buy_get_free_rules">Buy And Get Free (Offers)</option>
                        <option value="BOGO_category_rules">BOGO Category Rules</option>
                    </Select>                  
                    <input type="file" name="import_file" accept=".csv"/>
                </p>
                <p>
                    <input type="hidden" name="pwsix_action" value="import_settings" />

                    <?php wp_nonce_field('eha_import_export_nonce', 'eha_import_export_nonce'); ?>
                    <?php submit_button(__('Import'), 'secondary', 'eha-import', false); ?>
                </p>
            </form>
        </div><!-- .inside -->
    </div><!-- .postbox -->
    <script>
        jQuery(document).ready(
                function(){
                    jQuery('#confirm_delete').on('click',function(){ return confirm('Are you sure to delete all rules');});
                });
    </script>    
    <div class="postbox" id="pb5" name="pb5">
        <h3><span style="margin:10px;"><?php _e('Restore Tabs'); ?></span></h3>
        <div class="inside">
            <a href="admin.php?page=dynamic-pricing-main-page&tab=import_export&action=delete_all_rules" class="button" id="confirm_delete">Click Here to Delete All Rules from All Tabs</a>
        </div>
    </div><!-- .postbox -->
</div><!--end .wrap-->



