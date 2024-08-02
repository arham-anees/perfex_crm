<a href="#" class="btn btn-info pull-left new_setting_wcm_auto_store_sync">
	<?php echo _l('add'); ?>
</a>
<br>
<br>
<br>
<?php 

	$syn1 = '';	
	$syn2 = '';
	$syn3 = '';
	$syn4 = '';
	$syn5 = '';
	$syn6 = '';
	$syn7 = '';
	$syn8 = '';
?>

<table class="table dt-table table-store_sync_v2">
 <thead>
      <th><?php echo _l('store'); ?></th>
      <th><?php echo _l('sync_omni_sales_products'); ?></th>
      <th><?php echo _l('sync_omni_sales_inventorys'); ?></th>
      <th><?php echo _l('price_crm_woo'); ?></th>
      <th><?php echo _l('sync_omni_sales_description'); ?></th>
      <th><?php echo _l('sync_omni_sales_images'); ?></th>
      <th><?php echo _l('sync_omni_sales_orders'); ?></th>
      <th><?php echo _l('options'); ?></th>
 </thead>
<tbody>
  <?php foreach($setting_woo_store as $setting){ 
  	$time1 = $setting['sync_omni_sales_products'] == 1 ? _l('setting_on') : _l('setting_off');
  	$time2 = $setting['sync_omni_sales_inventorys'] == 1 ? _l('setting_on'): _l('setting_off');
  	$time3 = $setting['price_crm_woo'] == 1 ? _l('setting_on'): _l('setting_off');
  	$time4 = $setting['sync_omni_sales_description'] == 1 ? _l('setting_on'): _l('setting_off');
  	$time5 = $setting['sync_omni_sales_images'] == 1 ? _l('setting_on'): _l('setting_off');
  	$time6 = $setting['sync_omni_sales_orders'] == 1 ? _l('setting_on'): _l('setting_off');

  	?>
    <tr>
      <td><?php echo new_html_entity_decode($setting['name_channel']); ?></td>
      <td><?php echo new_html_entity_decode($time1); ?></td>
      <td><?php echo new_html_entity_decode($time2); ?></td>
      <td><?php echo new_html_entity_decode($time3); ?></td>
      <td><?php echo new_html_entity_decode($time4); ?></td>
      <td><?php echo new_html_entity_decode($time5); ?></td>
      <td><?php echo new_html_entity_decode($time6); ?></td>
      <td><?php echo icon_btn('#', 'fa fa-edit', 'btn-default', [
                        'title' => _l('edit'),
                        'data-id' => new_html_entity_decode($setting['id']),
                        'data-store' => new_html_entity_decode($setting['store']),
                        'data-sync_omni_sales_products' => new_html_entity_decode($setting['sync_omni_sales_products']),
                        'data-sync_omni_sales_inventorys' => new_html_entity_decode($setting['sync_omni_sales_inventorys']),
                        'data-price_crm_woo' => new_html_entity_decode($setting['price_crm_woo']),
                        'data-sync_omni_sales_description' => new_html_entity_decode($setting['sync_omni_sales_description']),
                        'data-sync_omni_sales_images' => new_html_entity_decode($setting['sync_omni_sales_images']),
                        'data-sync_omni_sales_orders' => new_html_entity_decode($setting['sync_omni_sales_orders']),
                        'data-time1' => new_html_entity_decode($setting['time1']),
                        'data-time2' => new_html_entity_decode($setting['time2']),
                        'data-time3' => new_html_entity_decode($setting['time3']),
                        'data-time4' => new_html_entity_decode($setting['time4']),
                        'data-time5' => new_html_entity_decode($setting['time5']),
                        'data-time6' => new_html_entity_decode($setting['time6']),
                        'onclick' => 'update_setting_woo_store(this); return false;'
                    ]).''.icon_btn(site_url('affiliate/usercontrol/delete_sync_auto_store/'.$setting['id']), 'fa fa-remove', 'btn-danger _delete', [
                        'title' => _l('delete') 
                    ]); ?></td>
    </tr>
 <?php } ?>
</tbody>
</table>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title add_title"><?php echo _l('new_store_sync_auto'); ?></h4>
        <h4 class="modal-title edit_title hide"><?php echo _l('edit_store_sync_auto'); ?></h4>
      </div>
      <div class="modal-body">
		<?php echo form_open(site_url('affiliate/usercontrol/sync_auto_store'),array('id'=>'sync-auto-store-form')); ?>
          	<?php echo form_hidden('id'); ?>
			<div class="row">
          	<div class="col-md-12">
               <div class="form-group" app-field-wrapper="store">
                <label for="store" class="control-label"><?php echo _l('store'); ?></label>
                  <select id="store" name="store" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" data-actions-box="true" tabindex="-98">
                    <option value=""></option>
                    <?php foreach ($store as $key => $value){ ?>
                      <option value="<?php echo new_html_entity_decode($value['id']); ?>"><?php echo new_html_entity_decode($value['name_channel']); ?></option>
                    <?php } ?>
                  </select>
             </div>
            </div>
            </div>
			<div class="row">
				<div class="col-md-12">
					<h4><?php echo _l('crm_to_woocommerce_store'); ?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="<?php echo _l('note_setting_1'); ?>"></i></h4>
					<div class="col-md-12 position_partent">
				            <input type="checkbox" name="sync_omni_sales_products" id="sync_omni_sales_products"  <?php echo new_html_entity_decode($syn1); ?> />
				            <label for="sync_omni_sales_products"><?php echo _l('product_info_enable_disable') ?></label>
				            <input type="number" name="time1" class="pull-right cus_input" placeholder="<?php echo _l('num_of_minutes'); ?>" value="<?php echo new_html_entity_decode($minute_sync_product_info_time1)?>"/>
					</div>
				    <div class="col-md-12 position_partent">
				    	<div class="funkyradio-warning">
				            <input type="checkbox" name="sync_omni_sales_inventorys" id="sync_omni_sales_inventorys" <?php echo new_html_entity_decode($syn2); ?> />
				            <label for="sync_omni_sales_inventorys"><?php echo _l('inventory_info_enable_disable'); ?></label>
				            <input type="number" name="time2" class="pull-right cus_input" placeholder="<?php echo _l('num_of_minutes'); ?>" value="<?php echo new_html_entity_decode($minute_sync_inventory_info_time2)?>"/>
				        </div>
				    </div>
				    <div class="col-md-12 position_partent">
				    	<div class="funkyradio-warning">
				            <input type="checkbox" name="price_crm_woo" id="price_crm_woo" <?php echo new_html_entity_decode($syn3); ?> />
				            <label for="price_crm_woo"><?php echo _l('price_enable_disable'); ?></label>
				            <input type="number" name="time3" class="pull-right cus_input" placeholder="<?php echo _l('num_of_minutes'); ?>" value="<?php echo new_html_entity_decode($minute_sync_price_time3)?>"/>
				        </div>
				    </div>
				    <div class="col-md-12 position_partent">
				    	<div class="funkyradio-info">
				            <input type="checkbox" name="sync_omni_sales_description" id="sync_omni_sales_description"  <?php echo new_html_entity_decode($syn4); ?> />
				            <label for="sync_omni_sales_description"><?php echo _l('descripton_enable_disable'); ?></label>
				            <input type="number" name="time4" class="pull-right cus_input" placeholder="<?php echo _l('num_of_minutes'); ?>" value="<?php echo new_html_entity_decode($minute_sync_decriptions_time4)?>"/>
				        </div>
				    </div>
				    <div class="col-md-12 position_partent">
				    	 <div class="funkyradio-danger">
				            <input type="checkbox" name="sync_omni_sales_images" id="sync_omni_sales_images"  <?php echo new_html_entity_decode($syn5); ?> />
				            <label for="sync_omni_sales_images"><?php echo _l('product_image_enable_disable'); ?></label>
				            <input type="number" name="time5" class="pull-right cus_input" placeholder="<?php echo _l('num_of_minutes'); ?>" value="<?php echo new_html_entity_decode($minute_sync_images_time5)?>"/>
				        </div>
				    </div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
				<h4><?php echo _l('woocommerce_store_to_crm'); ?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="<?php echo _l('note_setting_2'); ?>"></i></h4>
			 		<div class="col-md-12 position_partent">
					   	<div class="funkyradio">
				            <div class="funkyradio-success">
				                <input type="checkbox" name="sync_omni_sales_orders" id="sync_omni_sales_orders" <?php echo new_html_entity_decode($syn1); ?> />
				                <label for="sync_omni_sales_orders"><?php echo _l('order_enable_disable'); ?></label>
				                <input type="number" name="time6" class="pull-right cus_input" placeholder="<?php echo _l('num_of_minutes'); ?>" value="<?php echo new_html_entity_decode($minute); ?>"/>

				            </div>
				        </div>
			 		</div>
				</div>
			</div>
      </div>
      <div class="modal-footer">
		<button type="submit" class="btn btn-primary pull-right"><?php echo _l('save'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close');?></button>
      </div>
	  <?php echo form_close(); ?>
    </div>

  </div>
</div>
  
