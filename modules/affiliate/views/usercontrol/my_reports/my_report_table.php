<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="commission_table" class="hide">
   <div class="row">
      <div class="clearfix"></div>
      <table class="table table-commission scroll-responsive">
         <thead>
            <tr>
               <th><?php echo _l('program_name'); ?></th>
               <th><?php echo _l('invoice_dt_table_heading_number'); ?></th>
               <th><?php echo _l('date_sold'); ?></th>
               <th><?php echo _l('customer'); ?></th>
               <th><?php echo _l('sale_amount'); ?></th>
               <th><?php echo _l('commission'); ?></th>
               <th><?php echo _l('type'); ?></th>
               <th><?php echo _l('invoice_dt_table_heading_status'); ?></th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <?php 
            $total = 0;
            $total_commission = 0;
            foreach($commissions as $commission){ 

               ?>
               <tr>
                  <td><?php echo new_html_entity_decode($commission['program_name']); ?></td>
                  <td><a href="<?php echo site_url('invoice/' . $commission['invoice_id'] . '/' . $commission['invoice_hash']); ?>" class="invoice-number"><?php echo format_invoice_number($commission['invoice_id']); ?></a></td>
                  <td><?php echo _dt($commission['datecreated']); ?></td>
                  <td><?php echo get_affiliate_full_name($commission['member_id']); ?></td>
                  <td><?php 
                     $total += $commission['total'];
                     echo app_format_money($commission['total'], $currency->name); ?></td>
                  <td><?php 
                     $total_commission += $commission['amount'];
                     echo app_format_money($commission['amount'], $currency->name); ?></td>
                  <td><?php echo _l($commission['type']); ?></td>

                    <td>
                     <?php
                        if ($commission['transaction_status'] == 1) {
                             $status_name = _l('waiting');
                             $label_class = 'info';
                         } elseif ($commission['transaction_status'] == 2) {
                             $status_name = _l('invoice_status_paid');
                             $label_class = 'success';
                         } else {
                             $status_name = _l('invoice_status_unpaid');
                             $label_class = 'default';
                         }
                 ?>
                  <span class="label label-<?php echo new_html_entity_decode($label_class); ?> s-status commission-status-<?php echo new_html_entity_decode($commission['transaction_status']); ?>"><?php echo new_html_entity_decode($status_name);  ?></span></td>
               </tr>
            <?php } ?>
            <tr>
               <td><?php echo _l('total'); ?></td>
               <td></td>
               <td></td>
               <td></td>
               <td class="total"><?php echo app_format_money($total, $currency->name); ?></td>
               <td class="total_commission"><?php echo app_format_money($total_commission, $currency->name); ?></td>
               <td></td>
               <td></td>
            </tr>
         </tfoot>
      </table>
   </div>
</div>
