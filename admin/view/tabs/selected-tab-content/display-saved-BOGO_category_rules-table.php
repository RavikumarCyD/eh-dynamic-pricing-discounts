<?php
$allrules = array();
$allrules = get_option("xa_dp_rules", array());
    $allrules = isset($allrules['BOGO_category_rules'])?$allrules['BOGO_category_rules']:array();
    ?>
    <table class="display_all_rules table widefat" style=" border-collapse: collapse;">
        <thead style="font-size: smaller;background-color: lightgrey;">
            <tr style=" border-bottom-style: solid; border-bottom-width: thin;">
            <th class="xa-table-header icon-move" style="font-size: 10px;padding:3px;word-wrap: break-word; width: 5px;">Drag</th>
            <th class="xa-table-header" style="padding:3px;width: 60px;"><?php esc_attr_e('Options', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style="word-wrap: break-word; width: 10px;"><?php esc_attr_e('Rule no.', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style=""><?php esc_attr_e('Offer Name', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style=""><?php esc_attr_e('Purchased Category', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style=""><?php esc_attr_e('Purchased Quantity', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style=""><?php esc_attr_e('Free Product', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style=""><?php esc_attr_e('Free Quantity', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style=""><?php esc_attr_e('Allowed Roles', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style=""><?php esc_attr_e('From Date', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style=""><?php esc_attr_e('To Date', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style="padding-right: 4px;padding-left: 4px;"><?php esc_attr_e('Adjustment', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style="padding-right: 4px;padding-left: 4px;"><?php esc_attr_e('For Emails', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style="padding-right: 4px;padding-left: 4px;"><?php esc_attr_e('Min Order Count', 'eh-dynamic-pricing-discounts'); ?></th>
            <th class="xa-table-header" style="padding-right: 4px;padding-left: 4px;"><?php esc_attr_e('Min Order Amt', 'eh-dynamic-pricing-discounts'); ?></th>
            
            </tr>
        </thead>
        <tbody style="font-size: smaller;">
            <?php
            $customclass = 'saved_row';
            if(empty($allrules))
            {
                echo '<tr class="' . $customclass . ' " style="border-bottom:lightgrey; border-bottom-style: solid; border-bottom-width: thin;">';
                echo '<td colspan=20> '.__('There are no rules created to create a rule click the "Add New Rule" button on top left-hand side.','eh-dynamic-pricing-discounts').'</td>';             
            }
            foreach ($allrules as $key => $value) {
                if (isset($_GET['delete']))
                    if ($key == $_GET['delete']) {
                        $customclass = 'deleting';
                    }

                if (!isset($value['adjustment'])) {
                    $value['adjustment'] = null;
                }
                echo '<tr class="' . $customclass . '" style="border-bottom: lightgrey; border-bottom-style: solid; border-bottom-width: thin;"><td class="icon-move " style="width:10px;cursor: move"></td>';
                echo '<td style="margin-left: 0px; margin-right: 0px; padding-left: 0px; padding-right: 0px;">';
                echo '<button class="editbtn"   type="submit" name="edit" value="' . $key . '" ></button>';
                echo '<button class="deletebtn" type="submit" name="delete" value="' . $key . '" ></button>';
                echo "</td>";

                echo '<td>' . $key . '</td>';
                foreach ($value as $key2 => $value2) {
                    if ($key2 == 'offer_name') {
                        echo "<td style=\"width:15%;\">";
                        if (!empty($value2))
                            echo $value2;
                        else
                            echo '  -  ';
                        echo "</td>";
                    }
                    elseif ($key2 == 'purchased_category_id') {
                        echo '<td style="width:15%;">';
                        foreach ($value2 as $cid => $qnty_and_checkon) {
                            if ($term = get_term_by('id', $cid, 'product_cat')) {
                                echo '<span style="float:none;display: table-row; margin-top:10px" class="product_name highlight" id=' . $cid . '>' . $term->name . '</span>';
                            }
                        }
                        echo "</td>";

                        echo '<td style="width:8%;">';
                        foreach ($value2 as $pid => $qnty_and_checkon) {
                            $tmp=explode(':',$qnty_and_checkon);
                            $qnty=isset($tmp[0])?$tmp[0]:0;
                            $checkon=isset($tmp[1])?$tmp[1]:'items';
                            echo '<span style="display: table-row;" class="product_quantity" id=' . $pid . '>' . $qnty .' '. $checkon. '</span>';
                        }
                        echo "</td>";
                    } elseif ($key2 == 'free_product_id') {
                        echo '<td style="width:15%;">';
                        foreach ($value2 as $pid => $qnty) {
                            $product = wc_get_product($pid);
                            if (empty($pid) || empty($product)) {
                                continue;
                            }
                            echo '<span style="float:none;display: table-row; margin-top:10px" class="product_name highlight" id=' . $pid . '>' . $product->get_formatted_name() . '</span>';
                        }
                        echo "</td>";

                        echo '<td style="width:5%;">';
                        foreach ($value2 as $pid => $qnty) {
                            $product = wc_get_product($pid);
                            if (empty($pid) || empty($product)) {
                                continue;
                            }
                            echo '<span style="display: table-row;" class="product_quantity" id=' . $pid . '>' . $qnty . '</span>';
                        }
                        echo "</td>";
                    }
                    elseif (!empty($value2) && is_array($value2) ) {
                        echo '<td style="width:15%;padding-right: 4px;padding-left: 4px;" class="product_name" id=' . implode(',', $value2) . '>';
                        foreach ($value2 as $val) {
                            if (!empty($val)) {
                                echo '<span class="highlight">' . $val . '</span></br>';
                            }
                        }
                        echo '</td>';
                    } else {
                        echo "<td style=\" \">";
                        if (!empty($value2))
                            echo $value2;
                        else
                            echo '  -  ';
                        echo "</td>";
                    }
                }

                echo '</tr>';
            }
            ?>
        </tbody>
        <tfoot></tfoot>
    </table>


    <?php
