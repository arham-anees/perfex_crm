<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
$status = af_get_status_by_index($order->status);   
?>
<div id="wrapper">
  <div class="content">
   <div class="row">
    <div class="col-md-12">
      <div class="panel_s accounting-template estimate">
        <div class="panel-body mtop10">
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-6">
                <h5><?php echo _l('order_number');  ?>: <?php  echo ( isset($order) ? $order->order_code : ''); ?></h5>
                <input type="hidden" name="order_number" value="<?php echo new_html_entity_decode($order->order_code); ?>">
                <span><?php echo _l('order_date');  ?>: <?php  echo ( isset($order) ? _dt($order->order_datecreated) : ''); 
              ?></span>
              <?php if(isset($order) && $order->invoice_id != ''){ ?>
                <h4><?php echo _l('invoice');  ?>: <a href="<?php echo admin_url('invoices/list_invoices/'.$order->invoice_id) ?>"><?php echo format_invoice_number($order->invoice_id); ?></a></h4>
              <?php } ?>
              <input type="hidden" name="order_code" value="<?php echo new_html_entity_decode($order->order_code); ?>">
            </div>
            <div class="col-md-6">
              <ul> 
                <li class="dropdown pull-right">
                  <button href="#" class="dropdown-toggle btn" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" >
                    <?php echo _l($status); ?>  <span class="caret" data-toggle="" data-placement="top" data-original-title="<?php echo _l('change_status'); ?>"></span>
                  </button>
                  <ul class="dropdown-menu animated fadeIn">
                    <li class="customers-nav-item-edit-profile">
                      <a href="#" class="change_status" data-status="0">
                        <?php echo _l('omni_draft'); ?>
                      </a> 
                      <a href="#" class="change_status" data-status="1">
                        <?php echo _l('processing'); ?>
                      </a>      
                      <a href="#" class="change_status" data-status="2">
                        <?php echo _l('pending_payment'); ?>
                      </a>
                      <a href="#" class="change_status" data-status="3">
                        <?php echo _l('confirm'); ?>
                      </a>
                      <a href="#" class="change_status" data-status="4">
                        <?php echo _l('shipping'); ?>
                      </a>
                      <a href="#" class="change_status" data-status="5">
                        <?php echo _l('finish'); ?>
                      </a>
                      <a href="#" class="change_status" data-status="6">
                        <?php echo _l('refund'); ?>
                      </a>
                      <a href="#" class="change_status" data-status="8">
                        <?php echo _l('omni_canceled'); ?>
                      </a>   
                      <a href="#" class="change_status" data-status="9">
                        <?php echo _l('omni_on_hold'); ?>
                      </a> 
                      <a href="#" class="change_status" data-status="10">
                        <?php echo _l('omni_failed'); ?>
                      </a>   
                    </li> 

                  </ul>
                </li>
              </ul>
              <?php 
          // if(isset($order) && $order->approve_status == 1){
          //         $status_name = _l('approved');
          //         $label_class = 'success';
          //       }elseif(isset($order) && $order->approve_status == 2){
          //         $status_name = _l('rejected');
          //         $label_class = 'danger';
          //        }else{
          //         $status_name = _l('not_yet_approve');
          //         $label_class = 'default';
          //       }
              ?>
              <!-- <span class="label label-<?php //echo new_html_entity_decode($label_class); ?> s-status pull-right order-status"><?php //echo new_html_entity_decode($status_name); ?></span> -->
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
              <hr>  
            </div>
            <br>
            <br>
            <br>
            <div class="clearfix"></div>
            <div class="col-md-4">
              <input type="hidden" name="customer" value="<?php echo new_html_entity_decode($order->customer); ?>">
              <h4 class="no-mtop">
                <i class="fa fa-user"></i>
                <?php echo _l('customer_details'); ?>
              </h4>
              <hr />
              <?php  echo ( isset($order) ? $order->company : ''); ?><br>
              <?php  echo ( isset($order) ? $order->phonenumber : ''); ?><br>
              <?php echo ( isset($order) ? $order->address : ''); ?><br>
              <?php echo ( isset($order) ? $order->city : ''); ?> <?php echo ( isset($order) ? $order->state : ''); ?><br>
              <?php echo isset($order) ? get_country_short_name($order->country) : ''; ?> <?php echo ( isset($order) ? $order->zip : ''); ?><br>
            </div>
            <div class="col-md-4">
              <h4 class="no-mtop">
                <i class="fa fa-map"></i>
                <?php echo _l('billing_address'); ?>
              </h4>
              <hr />
              <address class="invoice-html-customer-shipping-info">
                <?php echo isset($order) ? $order->billing_street : ''; ?>
                <br><?php echo isset($order) ? $order->billing_city : ''; ?> <?php echo isset($order) ? $order->billing_state : ''; ?>
                <br><?php echo isset($order) ? get_country_short_name($order->billing_country) : ''; ?> <?php echo isset($order) ? $order->billing_zip : ''; ?>
              </address>
            </div>
            <div class="col-md-4">
              <h4 class="no-mtop">
                <i class="fa fa-street-view"></i>
                <?php echo _l('shipping_address'); ?>
              </h4>
              <hr />
              <address class="invoice-html-customer-shipping-info">
                <?php echo isset($order) ? $order->shipping_street : ''; ?>
                <br><?php echo isset($order) ? $order->shipping_city : ''; ?> <?php echo isset($order) ? $order->shipping_state : ''; ?>
                <br><?php echo isset($order) ? get_country_short_name($order->shipping_country) : ''; ?> <?php echo isset($order) ? $order->shipping_zip : ''; ?>
              </address>
            </div>
            <div class="row">
              <?php
              $currency_name = '';
              if(isset($base_currency)){
                $currency_name = $base_currency->name;
              }
              $sub_total = 0;
              ?>
              <div class="clearfix"></div>
              <br><br>        
              <div class="invoice accounting-template">
                <div class="col-md-12 fr1">
                  <div class="table-responsive s_table">
                    <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop">
                      <thead>
                        <tr>
                          <th width="20%" align="left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_description_new_lines_notice'); ?>"></i> <?php echo _l('invoice_table_item_heading'); ?></th>
                          <th width="25%" align="left"><?php echo _l('invoice_table_item_description'); ?></th>
                          <?php

                          $qty_heading = _l('invoice_table_quantity_heading');
                          if(isset($order) && $order->show_quantity_as == 2 || isset($hours_quantity)){
                            $qty_heading = _l('invoice_table_hours_heading');
                          } else if(isset($order) && $order->show_quantity_as == 3){
                            $qty_heading = _l('invoice_table_quantity_heading') .'/'._l('invoice_table_hours_heading');
                          }
                          ?>
                          <th width="10%" align="center" class="qty"><?php echo new_html_entity_decode($qty_heading); ?></th>
                          <th width="15%" align="center"><?php echo _l('invoice_table_rate_heading'); ?></th>
                          <th width="20%" align="center"><?php echo _l('invoice_table_tax_heading'); ?></th>
                          <th width="10%" align="center"><?php echo _l('invoice_table_amount_heading'); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $sub_total = 0; 
                        $total_tax = 0;
                        ?>

                        <?php foreach ($order->items as $key => $item) { 
                          ?>
                          <tr class="main">
                            <td>
                              <?php echo new_html_entity_decode($item['description']); ?>
                            </td>
                            <td>
                              <?php echo new_html_entity_decode($item['long_description']); ?>
                            </td>
                            <td align="center" class="middle">
                              <?php echo new_html_entity_decode($item['qty']); ?>
                            </td>
                            <td align="center" class="middle">
                              <strong><?php 
                              echo app_format_money($item['rate'],'');
                            ?></strong>
                          </td>
                          <td align="center" class="middle">
                            <?php 
                            $tax_name = '';
                            $line_total = (int)$item['qty']*$item['rate'];
                            $sub_total += $line_total;
                            foreach ($item['taxs'] as $tax) {
                              if($tax_name == ''){
                                $tax_name .= $tax['taxname'];
                              }else{
                                $tax_name .= ', '.$tax['taxname'];
                              }

                              $total_tax += ($tax['taxrate']/100) * $line_total;
                            } 
                            echo new_html_entity_decode($tax_name);
                            ?>
                          </td>
                          <td align="center" class="middle">
                            <strong class="line_total_<?php echo new_html_entity_decode($key); ?>">
                              <?php
                              echo app_format_money($line_total,''); ?>
                            </strong>
                          </td>
                        </tr>
                      <?php     } ?>
                    </tbody>
                  </table>
                </div>

                <div class="col-md-4 col-md-offset-8">
                  <table class="table text-right">
                    <tbody>
                      <tr id="subtotal">
                        <td><span class="bold"><?php echo _l('invoice_subtotal'); ?> :</span>
                        </td>
                        <td class="subtotal_s">
                          <?php
                          echo app_format_money($sub_total,'').' '.$currency_name; ?>
                        </td>
                      </tr>
                      <tr>
                        <td><span class="bold"><?php echo _l('tax'); ?> :</span>
                        </td>
                        <td>
                          <?php echo app_format_money($total_tax,'').' '.$currency_name; ?>
                        </td>
                      </tr>
                      <tr>
                        <td><span class="bold"><?php echo _l('invoice_total'); ?> :</span>
                        </td>
                        <td class="total_s">                                      
                          <?php echo app_format_money($order->total,'').' '.$currency_name; ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>   

                <div class="col-12">
                  <a href="<?php echo admin_url('affiliate/affiliate_orders'); ?>" class="btn btn-default"><?php echo _l('close'); ?></a>
                  <?php if(!$order->invoice_id > 0){ ?>
                    <?php if(affiliate_has_permission('affiliate_orders', '', 'create')){ ?>
                      <a href="#"  onclick="create_invoice(<?php echo new_html_entity_decode($order->id); ?>); return false;" id="btn-create-invoice" class="btn btn-success pull-right mright10 _delete" data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('create_invoice'); ?></a>
                    <?php } ?>
                  <?php }else{ ?>
                    <a href="<?php echo admin_url('invoices#'.$order->invoice_id); ?>" class="btn pull-right"><?php echo _l('view_invoice'); ?></a>
                  <?php } ?>
              </div>
            </div>
          </div>
        </div>               
      </div>
    </div>
  </div>
</div>
</div>
</div>


<div class="modal fade" id="chosse" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="add-title"><?php echo _l('please_let_us_know_the_reason_for_canceling_the_order') ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
          <?php echo render_textarea('cancel_reason','cancel_reason',''); ?>
        </div>
      </div>
      <div class="clearfix">               
        <br>
        <br>
        <div class="clearfix">               
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
          <button type="button" data-status="8" class="btn btn-danger cancell_order"><?php echo _l('cancell'); ?></button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div><!-- /.modal -->


<?php init_tail(); ?>
</body>
</html>
