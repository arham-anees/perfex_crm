<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div class="content">
	<div class="row">
		<div class="col-md-12">
      <div class="panel_s accounting-template estimate">
        <div class="panel-body mtop10">
          <div class="row">
            <div class="col-md-12">
            <p class="bold p_style"><?php echo _l('my_orders'); ?></p>
            <hr class="hr_style"/>
              <div class="panel_s">
        <div class="col-md-6">
          <h5><?php echo _l('order_number');  ?>: <?php  echo ( isset($order) ? $order->order_code : ''); ?></h5>
          <span><?php echo _l('order_date');  ?>: <?php  echo ( isset($order) ? $order->datecreated : ''); ?></span>
          <?php if(isset($invoice)){ ?>
            <h4><?php echo _l('invoice');  ?>: <a href="<?php echo admin_url('invoices#'.$invoice->id) ?>"><?php echo new_html_entity_decode($order->invoice); ?></a></h4>
            
          <?php } ?>
          <input type="hidden" name="order_code" value="<?php echo new_html_entity_decode($order->order_code); ?>">
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
                          <a href="#">
                            <strong>
                              <?php   
                              echo new_html_entity_decode($item['description']);
                              ?>
                            </strong>
                          </a>
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
            </div>
            <div class="col-md-12 mtop15 mbot15">
              <hr>
              <a href="<?php echo site_url('affiliate/usercontrol/my_orders'); ?>" class="btn btn-default pull-right mright10"><?php echo _l('close'); ?></a>
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
</div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>
