<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
	<div class="content">
		<div class="row">
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body mtop10">
        <div class="row">
        <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                <?php echo render_input('link_register','Affiliate Store URL', site_url('affiliate/store/index/'.get_affiliate_user_code().'/1/0/0')); ?>
             </div>
                <a href="javascript:void(0)" onclick="copy_public_link(); return false;" class="btn btn-warning btn-with-tooltip mtop25" data-toggle="tooltip" title="<?php echo _l('copy_public_link'); ?>" data-placement="bottom"><i class="fa fa-clone "></i></a>
                <a href="<?php echo site_url('affiliate/store/index/'.get_affiliate_user_code().'/1/0/0'); ?>" class="btn btn-success btn-with-tooltip mtop25" data-toggle="tooltip" title="<?php echo _l('priview_store'); ?>" data-placement="bottom" target="_blank"><i class="fa fa-eye "></i> <?php echo _l('priview_store'); ?></a>
                <a href="#" onclick="add_product(); return false;" class="btn btn-info mtop25"><?php echo _l('add_product'); ?></a>
            </div>
            <table class="table dt-table">
             <thead>
                <th><?php echo _l('commodity_code'); ?></th>
                <th><?php echo _l('name'); ?></th>
                <th><?php echo _l('sku'); ?></th>
                <th><?php echo _l('group'); ?></th>
                <th><?php echo _l('unit'); ?></th>
                <th><?php echo _l('price'); ?></th>
                <th><?php echo _l('options'); ?></th>
             </thead>
            <tbody>
               <?php foreach($product_list as $product){ ?>
                <tr>
                  <td><a href="javascript:void(0)" onclick="product_detail(<?php echo new_html_entity_decode($product['product_id']); ?>, ''); return false;"><?php echo new_html_entity_decode($product['commodity_code']); ?></a></td>
                  <td><?php echo new_html_entity_decode($product['description']); ?></td>
                  <td><?php echo new_html_entity_decode($product['sku_code']); ?></td>
                  <td><?php echo get_affiliate_group_name($product['group_id']); ?></td>
                  <td><?php echo get_affiliate_unit_name($product['unit_id']); ?></td>
                  <td><?php echo app_format_money($product['rate'], $currency->name); ?></td>
                  <td><?php echo icon_btn(site_url('affiliate/usercontrol/delete_product/'.$product['user_product_id']), 'fa fa-remove', 'btn-danger _delete', [
                        'title' => _l('delete') 
                    ]); ?></td>
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
<div class="modal fade" id="add_product_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo _l('add_product'); ?></span>
                </h4>
            </div>
            <?php echo form_open('affiliate/usercontrol/add_product',array('id'=>'market-category-modal')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" id="report-time">
                          <label for="product[]"><?php echo _l('affiliate_product'); ?></label><br />
                          <select class="selectpicker" name="product[]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-actions-box="true" multiple="true" data-live-search="true">
                              <?php foreach ($products as $key => $value) { ?>
                                  <option value="<?php echo new_html_entity_decode($value['id']); ?>"><?php echo new_html_entity_decode($value['label']); ?></option>
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

<div class="modal fade" id="commodity_list-add-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog ht-dialog-width">
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">
                    <span><?php echo _l('affiliate_product'); ?></span>
                </h4>
            </div>
            <div class="modal-body" id="product_detail_body">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close') ?></button>
            </div>
          </div>
          </div>
        </div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>
