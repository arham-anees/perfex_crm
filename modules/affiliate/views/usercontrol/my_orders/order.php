<div class="content">
    <div class="row">
        <div class="panel_s accounting-template estimate">
          <?php
echo form_open($this->uri->uri_string(), array('id' => 'order-form', 'class' => '_transaction_form order-form'));
if (isset($order)) {
    echo form_hidden('isedit');
} ?>
          <div class="panel-body">
          <?php if (isset($order)) {?>
          <?php echo format_invoice_status($order->status); ?>
          <hr class="hr-panel-heading" />
          <?php }?>
          <?php hooks()->do_action('before_render_invoice_template');?>
          <?php if (isset($order)) {
    echo form_hidden('merge_current_invoice', $order->id);
}?>
      <div class="row">
         <div class="col-md-6">
            <div class="row">
              <div class="col-md-12 form-group">
                <label for="customer"><?php echo _l('client'); ?></label>
                <select name="customer" id="customer" class="selectpicker"data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                    <option value=""></option>
                    <?php foreach ($customers as $s) {?>
                    <option value="<?php echo new_html_entity_decode($s['userid']); ?>" <?php if (isset($order) && $order->customer == $s['userid']) {echo 'selected';}?>><?php echo new_html_entity_decode($s['company']); ?></option>
                      <?php }?>
                </select>
              </div>
               <div class="col-md-12">
               <hr class="hr-10" />
                  <?php include_once APP_MODULES_PATH . 'affiliate/views/usercontrol/my_orders/billing_and_shipping_template.php';?>
               </div>
               <div class="col-md-6">
                  <p class="bold"><?php echo _l('invoice_bill_to'); ?></p>
                  <address>
                     <span class="billing_street">
                     <?php $billing_street = (isset($order) ? $order->billing_street : '--');?>
                     <?php $billing_street = ($billing_street == '' ? '--' : $billing_street);?>
                     <?php echo new_html_entity_decode($billing_street); ?></span><br>
                     <span class="billing_city">
                     <?php $billing_city = (isset($order) ? $order->billing_city : '--');?>
                     <?php $billing_city = ($billing_city == '' ? '--' : $billing_city);?>
                     <?php echo new_html_entity_decode($billing_city); ?></span>,
                     <span class="billing_state">
                     <?php $billing_state = (isset($order) ? $order->billing_state : '--');?>
                     <?php $billing_state = ($billing_state == '' ? '--' : $billing_state);?>
                     <?php echo new_html_entity_decode($billing_state); ?></span>
                     <br/>
                     <span class="billing_country">
                     <?php $billing_country = (isset($order) ? get_country_short_name($order->billing_country) : '--');?>
                     <?php $billing_country = ($billing_country == '' ? '--' : $billing_country);?>
                     <?php echo new_html_entity_decode($billing_country); ?></span>,
                     <span class="billing_zip">
                     <?php $billing_zip = (isset($order) ? $order->billing_zip : '--');?>
                     <?php $billing_zip = ($billing_zip == '' ? '--' : $billing_zip);?>
                     <?php echo new_html_entity_decode($billing_zip); ?></span>
                  </address>
               </div>
               <div class="col-md-6">
                  <p class="bold"><?php echo _l('ship_to'); ?></p>
                  <address>
                     <span class="shipping_street">
                     <?php $shipping_street = (isset($order) ? $order->shipping_street : '--');?>
                     <?php $shipping_street = ($shipping_street == '' ? '--' : $shipping_street);?>
                     <?php echo new_html_entity_decode($shipping_street); ?></span><br>
                     <span class="shipping_city">
                     <?php $shipping_city = (isset($order) ? $order->shipping_city : '--');?>
                     <?php $shipping_city = ($shipping_city == '' ? '--' : $shipping_city);?>
                     <?php echo new_html_entity_decode($shipping_city); ?></span>,
                     <span class="shipping_state">
                     <?php $shipping_state = (isset($order) ? $order->shipping_state : '--');?>
                     <?php $shipping_state = ($shipping_state == '' ? '--' : $shipping_state);?>
                     <?php echo new_html_entity_decode($shipping_state); ?></span>
                     <br/>
                     <span class="shipping_country">
                     <?php $shipping_country = (isset($order) ? get_country_short_name($order->shipping_country) : '--');?>
                     <?php $shipping_country = ($shipping_country == '' ? '--' : $shipping_country);?>
                     <?php echo new_html_entity_decode($shipping_country); ?></span>,
                     <span class="shipping_zip">
                     <?php $shipping_zip = (isset($order) ? $order->shipping_zip : '--');?>
                     <?php $shipping_zip = ($shipping_zip == '' ? '--' : $shipping_zip);?>
                     <?php echo new_html_entity_decode($shipping_zip); ?></span>
                  </address>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="no-shadow">
               <?php $value = (isset($order) ? $order->note : '');?>
               <?php echo render_textarea('note', 'note', $value); ?>
            </div>
         </div>
      </div>
   </div>
   <?php $csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash(),
);
?>

     <input type="hidden" id="csrf_token_name" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
<div class="panel-body mtop10">
      <div class="row">
         <div class="col-md-4 mbot25">
            <div class="">
              <div class="items-select-wrapper">
                 <select name="item_select" class="selectpicker no-margin" data-width="100%" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                  <option value=""></option>
                  <?php foreach ($items as $item) {?>
                   <option value="<?php echo new_html_entity_decode($item['id']); ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'], 0, 200)) . '...'; ?>">(<?php echo app_format_number($item['rate']); ?>) <?php echo new_html_entity_decode($item['label']); ?></option>
                 <?php }?>
               </select>
             </div>
            </div>
         </div>
         <?php if (!isset($order_from_project) && isset($billable_tasks)) {
    ?>
         <div class="col-md-3">
            <div class="form-group select-placeholder input-group-select form-group-select-task_select popover-250">
              <div class="input-group input-group-select">
               <select name="task_select" data-live-search="true" id="task_select" class="selectpicker no-margin _select_input_group" data-width="100%" data-none-selected-text="<?php echo _l('bill_tasks'); ?>">
                  <option value=""></option>
                  <?php foreach ($billable_tasks as $task_billable) {
        ?>
                  <option value="<?php echo new_html_entity_decode($task_billable['id']); ?>"<?php if ($task_billable['started_timers'] == true) {?>disabled class="text-danger important" data-subtext="<?php echo _l('invoice_task_billable_timers_found'); ?>" <?php } else {
            $task_rel_data  = get_relation_data($task_billable['rel_type'], $task_billable['rel_id']);
            $task_rel_value = get_relation_values($task_rel_data, $task_billable['rel_type']);
            ?>
                     data-subtext="<?php echo new_html_entity_decode($task_billable['rel_type']) == 'project' ? '' : new_html_entity_decode($task_rel_value['name']); ?>" <?php }?>><?php echo new_html_entity_decode($task_billable['name']); ?></option>
                  <?php }?>
               </select>
                <div class="input-group-addon input-group-addon-bill-tasks-help">
                  <?php
if (isset($order) && !empty($order->project_id)) {
        $help_text = _l('showing_billable_tasks_from_project') . ' ' . get_project_name_by_id($order->project_id);
    } else {
        $help_text = _l('invoice_task_item_project_tasks_not_included');
    }
    echo '<span class="pointer popover-invoker" data-container=".form-group-select-task_select"
                      data-trigger="click" data-placement="top" data-toggle="popover" data-content="' . $help_text . '">
                      <i class="fa fa-question-circle"></i></span>';
    ?>
                </div>
               </div>
            </div>
         </div>
         <?php }?>
         <div class="col-md-<?php if (!isset($order_from_project)) {echo 5;} else {echo 8;}?> text-right show_quantity_as_wrapper">
            <div class="mtop10">
               <span><?php echo _l('show_quantity_as'); ?> </span>
               <div class="radio radio-primary radio-inline">
                  <input type="radio" value="1" id="sq_1" name="show_quantity_as" data-text="<?php echo _l('invoice_table_quantity_heading'); ?>" <?php if (isset($order) && $order->show_quantity_as == 1) {echo 'checked';} else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {echo 'checked';}?>>
                  <label for="sq_1"><?php echo _l('quantity_as_qty'); ?></label>
               </div>
               <div class="radio radio-primary radio-inline">
                  <input type="radio" value="2" id="sq_2" name="show_quantity_as" data-text="<?php echo _l('invoice_table_hours_heading'); ?>" <?php if (isset($order) && $order->show_quantity_as == 2 || isset($hours_quantity)) {echo 'checked';}?>>
                  <label for="sq_2"><?php echo _l('quantity_as_hours'); ?></label>
               </div>
               <div class="radio radio-primary radio-inline">
                  <input type="radio" value="3" id="sq_3" name="show_quantity_as" data-text="<?php echo _l('invoice_table_quantity_heading'); ?>/<?php echo _l('invoice_table_hours_heading'); ?>" <?php if (isset($order) && $order->show_quantity_as == 3 || isset($qty_hrs_quantity)) {echo 'checked';}?>>
                  <label for="sq_3"><?php echo _l('invoice_table_quantity_heading'); ?>/<?php echo _l('invoice_table_hours_heading'); ?></label>
               </div>
            </div>
         </div>
      </div>
      <?php if (isset($order_from_project)) {echo '<hr class="no-mtop" />';}?>
      <div class="table-responsive s_table">
         <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop">
            <thead>
               <tr>
                  <th></th>
                  <th width="20%" align="left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_description_new_lines_notice'); ?>"></i> <?php echo _l('invoice_table_item_heading'); ?></th>
                  <th width="25%" align="left"><?php echo _l('invoice_table_item_description'); ?></th>
                  <?php
$custom_fields = get_custom_fields('items');
foreach ($custom_fields as $cf) {
    echo '<th width="15%" align="left" class="custom_field">' . $cf['name'] . '</th>';
}
$qty_heading = _l('invoice_table_quantity_heading');
if (isset($order) && $order->show_quantity_as == 2 || isset($hours_quantity)) {
    $qty_heading = _l('invoice_table_hours_heading');
} else if (isset($order) && $order->show_quantity_as == 3) {
    $qty_heading = _l('invoice_table_quantity_heading') . '/' . _l('invoice_table_hours_heading');
}
?>
                  <th width="10%" align="right" class="qty"><?php echo new_html_entity_decode($qty_heading); ?></th>
                  <th width="15%" align="right"><?php echo _l('invoice_table_rate_heading'); ?></th>
                  <th width="20%" align="right"><?php echo _l('invoice_table_tax_heading'); ?></th>
                  <th width="10%" align="right"><?php echo _l('invoice_table_amount_heading'); ?></th>
                  <th align="center"><i class="fa fa-cog"></i></th>
               </tr>
            </thead>
            <tbody>
               <tr class="main">
                  <td></td>
                  <td>
                     <textarea name="description" class="form-control" rows="4" placeholder="<?php echo _l('item_description_placeholder'); ?>"></textarea>
                  </td>
                  <td>
                     <textarea name="long_description" rows="4" class="form-control" placeholder="<?php echo _l('item_long_description_placeholder'); ?>"></textarea>
                  </td>
                  <?php echo render_custom_fields_items_table_add_edit_preview(); ?>
                  <td>
                     <input type="number" name="quantity" min="0" value="1" class="form-control" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">
                     <input type="text" placeholder="<?php echo _l('unit'); ?>" data-toggle="tooltip" data-title="e.q kg, lots, packs" name="unit" class="form-control input-transparent text-right">
                  </td>
                  <td>
                     <input type="number" name="rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
                  </td>
                  <td>
                     <?php
$default_tax = unserialize(get_option('default_tax'));
$select      = '<select class="selectpicker display-block tax main-tax" data-width="100%" name="taxname" multiple data-none-selected-text="' . _l('no_tax') . '">';
foreach ($taxes as $tax) {
    $selected = '';
    if (is_array($default_tax)) {
        if (in_array($tax['name'] . '|' . $tax['taxrate'], $default_tax)) {
            $selected = ' selected ';
        }
    }
    $select .= '<option value="' . $tax['name'] . '|' . $tax['taxrate'] . '"' . $selected . 'data-taxrate="' . $tax['taxrate'] . '" data-taxname="' . $tax['name'] . '" data-subtext="' . $tax['name'] . '">' . $tax['taxrate'] . '%</option>';
}
$select .= '</select>';
echo new_html_entity_decode($select);
?>
                  </td>
                  <td></td>
                  <td>
                     <?php
$new_item = 'undefined';
if (isset($order)) {
    $new_item = true;
}
?>
                     <button type="button" onclick="add_item_to_table('undefined','undefined',<?php echo new_html_entity_decode($new_item); ?>); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                  </td>
               </tr>
               <?php if (isset($order) || isset($add_items)) {
    $i               = 1;
    $items_indicator = 'newitems';
    if (isset($order)) {
        $add_items       = $order->items;
        $items_indicator = 'items';
    }
    foreach ($add_items as $item) {

        $manual    = false;
        $table_row = '<tr class="sortable item">';
        $table_row .= '<td class="dragger">';
        if (!is_numeric($item['qty'])) {
            $item['qty'] = 1;
        }
        $order_item_taxes = get_affiliate_order_item_taxes($item['id']);
        // passed like string
        if ($item['id'] == 0) {
            $order_item_taxes = $item['taxname'];
            $manual           = true;
        }
        $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);
        $amount = $item['rate'] * $item['qty'];
        $amount = app_format_number($amount);
        // order input
        $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
        $table_row .= '</td>';
        $table_row .= '<td class="bold description"><textarea name="' . $items_indicator . '[' . $i . '][description]" class="form-control" rows="5">' . clear_textarea_breaks($item['description']) . '</textarea></td>';
        $table_row .= '<td><textarea name="' . $items_indicator . '[' . $i . '][long_description]" class="form-control" rows="5">' . clear_textarea_breaks($item['long_description']) . '</textarea></td>';

        $table_row .= render_custom_fields_items_table_in($item, $items_indicator . '[' . $i . ']');

        $table_row .= '<td><input type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="' . $items_indicator . '[' . $i . '][qty]" value="' . $item['qty'] . '" class="form-control">';

        $unit_placeholder = '';
        if (!$item['unit']) {
            $unit_placeholder = _l('unit');
            $item['unit']     = '';
        }

        $table_row .= '<input type="text" placeholder="' . $unit_placeholder . '" name="' . $items_indicator . '[' . $i . '][unit]" class="form-control input-transparent text-right" value="' . $item['unit'] . '">';

        $table_row .= '</td>';
        $table_row .= '<td class="rate"><input type="number" data-toggle="tooltip" title="' . _l('numbers_not_formatted_while_editing') . '" onblur="calculate_total();" onchange="calculate_total();" name="' . $items_indicator . '[' . $i . '][rate]" value="' . $item['rate'] . '" class="form-control"></td>';
        $table_row .= '<td class="taxrate">' . $this->misc_model->get_taxes_dropdown_template('' . $items_indicator . '[' . $i . '][taxname][]', $order_item_taxes, 'invoice', $item['id'], true, $manual) . '</td>';
        $table_row .= '<td class="amount" align="right">' . $amount . '</td>';
        $table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
        if (isset($item['task_id'])) {
            if (!is_array($item['task_id'])) {
                $table_row .= form_hidden('billed_tasks[' . $i . '][]', $item['task_id']);
            } else {
                foreach ($item['task_id'] as $task_id) {
                    $table_row .= form_hidden('billed_tasks[' . $i . '][]', $task_id);
                }
            }
        } else if (isset($item['expense_id'])) {
            $table_row .= form_hidden('billed_expenses[' . $i . '][]', $item['expense_id']);
        }
        $table_row .= '</tr>';
        echo new_html_entity_decode($table_row);
        $i++;
    }
}
?>
            </tbody>
         </table>
      </div>
      <div class="col-md-8 col-md-offset-4">
         <table class="table text-right">
            <tbody>
               <tr id="subtotal">
                  <td><span class="bold"><?php echo _l('invoice_subtotal'); ?> :</span>
                  </td>
                  <td class="subtotal">
                  </td>
               </tr>
               <tr class="tax-area" id="tax_area">

               </tr>
               <tr>
                  <td><span class="bold"><?php echo _l('invoice_total'); ?> :</span>
                  </td>
                  <td class="total">
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
      <div id="removed-items"></div>
   </div>
   <div class="panel-body bottom-transaction">
          <div class="btn-group dropup pull-right">
            <button type="submit" class="btn-tr btn btn-info"><?php echo _l('submit'); ?></button>
          </div>
         </div>
      <?php echo form_close(); ?>
      </div>
  </div>
</div>