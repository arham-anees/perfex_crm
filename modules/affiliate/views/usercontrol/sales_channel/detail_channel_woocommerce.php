<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel_s">
        <div class="panel-body">
          <div class="horizontal-scrollable-tabs mb-5">
            <div class="col-md-12">
              <?php if($status == 0 ){ ?>
                <span class="label label-primary pull-right status-sync"><?php echo _l('normal_state'); ?></span>
              <?php }elseif($status == 1) { ?>  
                <span class="label label-danger pull-right status-sync"><?php echo _l('wait_for_sync'); ?></span>
              <?php }elseif($status == 2) { ?>
                <span class="label label-success pull-right status-sync"><?php echo _l('sync_success'); ?></span>
              <?php } ?>
              <h4><i class="fa fa-list-ul">&nbsp;&nbsp;</i><?php echo new_html_entity_decode($title); ?></h4>
              <br>
            </div>
<div class="col-md-12"> 
<a href="#" onclick="add_product(); return false;" class="btn btn-info pull-left">
    <?php echo _l('add'); ?>
</a>
<input type="hidden" name="id" value="<?php echo new_html_entity_decode($id); ?>">


<a href="Javascript:void(0);" id="toggle_popup_woo" class="btn btn-success display-block pull-right"><i class="fa fa-download"></i><?php echo ' '._l('download').' '; ?><i class="fa fa-caret-down"></i>
</a>
<h2 class="pull-right m-0">|</h2>
<a href="Javascript:void(0);" id="toggle_popup_crm" class="btn btn-info display-block pull-right"><i class="fa fa-refresh"></i><?php echo ' '._l('sync_to').' '; ?><i class="fa fa-caret-down"></i>
</a>
<ul id="popup_approval" class="dropdown-menu dropdown-menu-right">
  <li>
    <br>
    <div class="col-md-12">

    <a href="#" onclick="sync_all(this); return false;" data-id="<?php echo new_html_entity_decode($id); ?>" data-toggle="tooltip" data-original-title="<?php echo _l("sync_all") ?>" class="btn btn-info pull-right display-block mright5"><i class="fa fa fa-refresh  " data-toggle="dropdown" aria-expanded="false"></i><?php echo ' '._l('sync_all'); ?></a>

    <a href="#" onclick="sync_decriptions_synchronization(this); return false;" data-id="<?php echo new_html_entity_decode($id); ?>" class="btn btn-info pull-right display-block mright5" data-toggle="tooltip" data-placeme="top" data-original-title="<?php echo _l("sync_decriptions") ?>" ><i class="fa fa-info" data-toggle="dropdown" aria-expanded="false"></i><?php echo ' '._l('long_decriptions'); ?>
    </a>
    <a href="#" onclick="sync_images_synchronization(this); return false;" data-id="<?php echo new_html_entity_decode($id); ?>" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo _l("sync_images") ?>"  class="btn btn-info pull-right display-block mright5"><i class="fa fa-picture-o" data-toggle="dropdown" aria-expanded="false"></i><?php echo ' '._l('images'); ?></a>

    <a href="#" onclick="sync_price(this); return false;" data-id="<?php echo new_html_entity_decode($id); ?>" data-toggle="tooltip" data-original-title="<?php echo _l("sync_price") ?>" class="btn btn-info pull-right display-block mright5"><i class="fa fa fa-money" data-toggle="dropdown" aria-expanded="false"></i><?php echo ' '._l('sync_price'); ?></a>

    <a href="#" onclick="sync_inventory_synchronization(this); return false;" data-id="<?php echo new_html_entity_decode($id); ?>" data-toggle="tooltip" data-original-title="<?php echo _l("sync_from_store") ?>" class="btn btn-info pull-right display-block mright5"><i class="fa fa-snowflake-o" data-toggle="dropdown" aria-expanded="false"></i><?php echo ' '._l('inventory_sync'); ?></a>

    <a href="#" data-id="<?php echo new_html_entity_decode($id); ?>" class="btn btn-info pull-right display-block mright5 link sync_products_woo" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo _l("sync_products_to_store") ?>"><i class="fa fa-forward"></i><?php echo ' '._l('product_no_images'); ?></a>
    </div>
    <br>&nbsp;<br/>
  </li>
 </ul>

 <ul id="popup_woo" class="dropdown-menu dropdown-menu-right">
  <li>
    <br>
    <div class="col-md-12">
    <a href="#" onclick="sync_store(this); return false;" data-id="<?php echo new_html_entity_decode($id); ?>" class="btn btn-success pull-center display-block mright5 orders-woo" data-toggle="tooltip" data-placeme="top" data-original-title="<?php echo _l("sync_from_the_system_to_the_store") ?>"><i class="fa fa-first-order" data-toggle="dropdown" aria-expanded="false"></i><?php echo ' '._l('order'); ?>
    </a>
    </div>
    <br>&nbsp;<br/>
  </li>
 </ul>


<div class="clearfix"></div><br>
</div>
<div id="box-loadding">
</div>
<div class="col-md-12">
<table class="table dt-table table-product-woocommerce">
   <thead>
      <th class="not-export sorting_disabled" rowspan="1" colspan="1" aria-label=" - "><span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="product-woocommerce"><label></label></div></th>
      <th><?php echo _l('product_code'); ?></th>
      <th><?php echo _l('product_name'); ?></th>
      <th><?php echo _l('price'); ?></th>
      <th><?php echo _l('price_on_store'); ?></th>
      <th><?php echo _l('options'); ?></th>
   </thead>
  <tbody>
    <?php foreach($list_product as $product){ ?>
      <tr>
          <td>
            <div class="checkbox"><input type="checkbox" value="<?php echo new_html_entity_decode($product['product_id']); ?>"><label></label></div>
          </td>
          <td><a href="javascript:void(0)" onclick="product_detail(<?php echo new_html_entity_decode($product['product_id']); ?>, ''); return false;"><?php echo new_html_entity_decode($product['commodity_code']); ?></a></td>
          <td><?php echo new_html_entity_decode($product['description']); ?></td>
          <td><?php echo new_html_entity_decode($product['rate']); ?></td>
          <td><?php echo new_html_entity_decode($product['prices']); ?></td>
          <td><?php echo icon_btn('#', 'fa fa-eye', 'btn-default', [
              'title' => _l('view'),
              'onclick' => 'product_detail('.$product['product_id'].'); return false;',
          ]).''.icon_btn('#', 'fa fa-edit', 'btn-default', [
              'title' => _l('edit'),
              'onclick' => 'update_product_woo(this); return false;',
              'data-price_on_store' => $product['prices'],
              'data-description' => $product['description'],
              'data-commodity_code' => $product['commodity_code'],
              'data-id' => $product['woocommere_product_id'],
          ]).''.icon_btn(site_url('affiliate/usercontrol/delete_product_store/'.$product['woocommere_product_id'].'/'.$product['woocommere_channel_id']), 'fa fa-remove', 'btn-danger _delete', [
              'title' => _l('delete') 
          ]); ?></td>
      </tr>
   <?php } ?>
  </tbody>
</table> 
</div>
            <div class="col-md-12">
              <a href="<?php echo site_url('affiliate/usercontrol/sales_channel'); ?>" class="btn btn-danger"><?php echo _l('close'); ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo form_hidden('check'); ?>
<?php echo form_hidden('check_product'); ?>
<div class="modal fade" id="chose_product" tabindex="-1" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
                <span class="add-title"><?php echo _l('add_product'); ?></span>
                <span class="update-title hide"><?php echo _l('update_product'); ?></span>
            </h4>
        </div>
    <?php echo form_open(site_url('affiliate/usercontrol/add_product_channel_wcm'),array('id'=>'form_add_product')); ?>             
        <div class="modal-body">
           <div class="row">
            <input type="hidden" name="woocommere_channel_id" value="<?php echo new_html_entity_decode($id); ?>">
            <input type="hidden" id="id" name="id" value="">
            <div class="col-md-12 group_product_id">
               <div class="form-group" app-field-wrapper="group_product_id">
                <label for="group_product_id" class="control-label"><?php echo _l('group_product'); ?></label>
                  <select id="group_product_id" name="group_product_id" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" data-actions-box="true" tabindex="-98" onchange="get_list_product(this); return false;">
                    <option value=""></option>
                    <?php foreach ($group_product as $key => $value){ ?>
                      <option value="<?php echo new_html_entity_decode($value['id']); ?>"><?php echo new_html_entity_decode($value['commodity_group_code'].' # '.$value['name']); ?></option>
                    <?php } ?>
                  </select>
             </div>
            </div>
            <div class="col-md-12 product_id">
               <div class="form-group" app-field-wrapper="product_id">
                <label for="product_id" class="control-label"><?php echo _l('affiliate_product'); ?></label>
                  <select id="product_id" name="product_id[]" class="selectpicker" multiple  data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" data-actions-box="true" tabindex="-98">
                    <?php foreach ($products as $key => $value){ ?>
                      <option value="<?php echo new_html_entity_decode($value['id']); ?>"><?php echo new_html_entity_decode($value['commodity_code'].' # '.$value['description']); ?></option>
                    <?php } ?>
                  </select>
             </div>
            </div>
            <div class="col-md-12 product_detail">
            <table class="table border table-striped no-margin">
              <tbody>
                <tr class="project-overview">
                  <td class="bold" width="30%"><?php echo _l('product_code'); ?></td>
                  <td id="product_code"></td>
                </tr>
                <tr class="project-overview">
                  <td class="bold"><?php echo _l('product_name'); ?></td>
                  <td id="product_name"></td>
                </tr>
                <tr class="project-overview">
                  <td class="bold"><?php echo _l('price'); ?></td>
                  <td><?php 
                    $arrAtt = array();
                        $arrAtt['data-type']='currency';
                        echo render_input('prices','','','text',$arrAtt);
                  ?></td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info _delete"><?php echo _l('submit'); ?></button>
        </div>
        <?php echo form_close(); ?>                 
      </div>
    </div>
</div>

<div class="popup" data-popup="popup-1">
  <div class="popup-inner">
    <div class="popup-scroll">
      <div class="col-md-12">
        <button class="btn btn-success mx-3 sync_products_from_info_woo cus_btn" data-id="<?php echo new_html_entity_decode($id); ?>" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo _l("synchronize_product_information_basic") ?>"><i class="fa fa-refresh" aria-hidden="true" data-toggle="dropdown" aria-expanded="false"></i>  <?php echo _l('synchronize_product_information_basic'); ?></button>
        </div>
        <br>
        <br>
        <br>
        <div class="col-md-12 w-sync">
          <button class="btn btn-primary mx-3 sync_products_from_woo cus_btn" data-id="<?php echo new_html_entity_decode($id); ?>" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo _l("synchronize_product_information_full") ?>"><i class="fa fa-refresh" aria-hidden="true" data-toggle="dropdown" aria-expanded="false"></i> <?php echo _l('synchronize_product_information_full'); ?></button>
          <a href="#" data-toggle="tooltip" data-original-title="<?php echo _l("warning_may_take_longer") ?>" class="btn btn-danger pull-right btn-icon">
            <i class="fa fa-question-circle" aria-hidden="true" data-toggle="dropdown" aria-expanded="false"></i>
          </a>
        </div>
        <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
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