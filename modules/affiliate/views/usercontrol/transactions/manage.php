<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
	<div class="content">
		<div class="row">
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body mtop10">
        <div class="row">
        <div class="col-md-12">
        <p class="bold p_style"><?php echo _l('transactions'); ?></p>
        <hr class="hr_style"/>
        
        <a href="#" onclick="new_market_category(); return false;" class="btn btn-info mbot10"><?php echo _l('withdraw_all_selected'); ?></a>

        <table class="table dt-table table-transactions">
             <thead>
                  <th class="not-export sorting_disabled" rowspan="1" colspan="1" aria-label=" - "><span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="transactions"><label></label></div></th>
                  <th><?php echo _l('date'); ?></th>
                  <th><?php echo _l('user'); ?></th>
                  <th><?php echo _l('order'); ?></th>
                  <th><?php echo _l('invoice'); ?></th>
                  <th><?php echo _l('commission'); ?></th>
                  <th><?php echo _l('type'); ?></th>
                  <th><?php echo _l('status'); ?></th>
             </thead>
            <tbody>
              <?php foreach($transactions as $transaction){ ?>
                <tr>
                  <td>
                    <div class="checkbox"><input type="checkbox" <?php if($transaction['status'] != 0){ echo 'disabled'; }?> value="<?php echo new_html_entity_decode($transaction['id']); ?>" data-amount="<?php echo new_html_entity_decode($transaction['amount']); ?>"><label></label></div></td>
                  <td><?php echo _d($transaction['datecreated']); ?></td>
                  <td><?php echo new_html_entity_decode($transaction['username']); ?></td>
                  <td><?php echo new_html_entity_decode($transaction['order_id']); ?></td>
                  <td><?php echo format_invoice_number($transaction['invoice_id']); ?></td>
                  <td><?php echo app_format_money($transaction['amount'], $currency->name); ?></td>
                  <td><?php echo _l($transaction['type']); ?></td>
                  <?php 
                  if($transaction['status'] == 1){
                      $status_name = _l('waiting');
                      $label_class = 'info';
                  }elseif($transaction['status'] == 2){
                      $status_name = _l('invoice_status_paid');
                      $label_class = 'success';
                  }else{
                      $status_name = _l('invoice_status_unpaid');
                      $label_class = 'default';
                  }
                   ?>
                  <td><span class="label label-<?php echo new_html_entity_decode($label_class); ?> s-status commission-status-<?php echo new_html_entity_decode($transaction['status']); ?>"><?php echo new_html_entity_decode($status_name); ?></span></td>
                </tr>
             <?php } ?>
            </tbody>
         </table> 
        </div>
      </div>
        </div>
        </div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="market_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo _l('withdraw'); ?></span>
                </h4>
            </div>
            <?php echo form_open('affiliate/usercontrol/add_withdraw',array('id'=>'market-category-modal')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo form_hidden('transaction_ids'); ?>
                        <?php echo render_input('total', 'total', '', 'text', ['readonly' => true]); ?>
                        <div class="form-group" id="report-time">
                          <label for="paymentmode"><?php echo _l('payment_mode'); ?></label><br />
                          <select class="selectpicker" name="paymentmode" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                              <?php foreach ($payment_modes as $key => $value) { ?>
                                  <option value="<?php echo new_html_entity_decode($value['id']); ?>"><?php echo new_html_entity_decode($value['name']); ?></option>
                              <?php } ?>
                          </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button group="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>