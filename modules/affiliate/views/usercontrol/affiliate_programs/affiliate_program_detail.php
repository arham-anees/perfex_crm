<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div class="content">
	<div class="row">
		<div class="col-md-12">
      <div class="panel_s accounting-template estimate">
        <div class="panel-body mtop10">
          <div class="row">
            <div class="col-md-12">
            <p class="bold p_style"><?php echo _l('affiliate_program'); ?></p>
            <hr class="hr_style"/>
                <div class="panel panel-info">
                  <div class="panel-heading"><?php echo _l('general_information'); ?></div>
                  <div class="panel-body">
                    <table class="table border table-striped no-margin">
                    <tbody>
                      <tr class="project-overview">
                        <td class="bold" width="30%"><?php echo _l('name'); ?></td>
                        <td><?php  echo (isset($affiliate_program) ? $affiliate_program->name : ''); ?></td>
                      </tr>
                      <tr class="project-overview">
                        <td class="bold" width="30%"><?php echo _l('effective_date'); ?></td>
                        <td><?php  echo (isset($affiliate_program) ? _d($affiliate_program->from_date) : ''); ?></td>
                      </tr>
                      <tr class="project-overview">
                        <td class="bold" width="30%"><?php echo _l('expiration_date'); ?></td>
                        <td><?php  echo (isset($affiliate_program) ? _d($affiliate_program->to_date) : ''); ?></td>
                      </tr>
                      <tr class="project-overview">
                        <td class="bold" width="30%"><?php echo _l('priority'); ?></td>
                        <td><?php  echo (isset($affiliate_program) ? $affiliate_program->priority : ''); ?></td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                </div>
              <div class="row">

                <?php if($affiliate_program->enable_discount == 'enable'){ ?>
                  <?php 
                      $discount_policy_type = '';
                      if(isset($affiliate_program)){
                        switch ($affiliate_program->discount_policy_type) {
                          case '1':
                            $discount_policy_type = _l('calculated_as_ladder');
                            break;
                          case '2':
                            $discount_policy_type = _l('calculated_as_percentage');
                            break;
                          case '3':
                            $discount_policy_type = _l('calculated_by_the_product');
                            break;
                          case '4':
                            $discount_policy_type = _l('calculated_product_as_ladder');
                            break;
                          default:
                            break;
                        }
                      } ?>
                <div class="col-md-6">
                  <div class="panel panel-info">
                    <div class="panel-heading"><?php echo _l('discount');  ?></div>
                    <div class="panel-body">
                      <?php if($affiliate_program->discount_policy_type == 1){ ?>
                        <h5><?php echo new_html_entity_decode($discount_policy_type); ?></h5>
                         <table class="table border table-striped no-margin">
                          <thead>
                            <th><strong><?php echo _l('amount'); ?></strong></th>
                            <th><strong><?php echo _l('discount'); ?></strong></th>
                          </thead>
                        <tbody>
                          <?php 
                           $setting = json_decode($affiliate_program->discount_ladder_setting);
                          foreach ($setting as $key => $value) {
                            $amount = '';
                            if($affiliate_program->discount_type == 'fixed'){
                              $amount = app_format_money($value->discount_percent_enjoyed_ladder, $currency->name);
                            }else {
                              $amount = $value->discount_percent_enjoyed_ladder.'%';
                            }
                            ?>
                            <tr class="project-overview">
                              <td><?php echo new_html_entity_decode(($value->discount_from_amount != '' ? $value->discount_from_amount : '0').' - '. ($value->discount_to_amount != '' ? $value->discount_to_amount : '&infin;')); ?></td>
                              <td><?php  echo new_html_entity_decode($amount); ?></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table> 
                      <?php } ?>
                      <?php if($affiliate_program->discount_policy_type == 2){ ?>
                         <table class="table border table-striped no-margin">
                        <tbody>
                          <tr class="project-overview">
                            <td class="bold" width="30%"><?php echo _l('discount_policy_type'); ?></td>
                            <td><?php echo new_html_entity_decode($discount_policy_type); ?></td>
                          </tr>
                          <tr class="project-overview">
                            <td class="bold" width="30%"><?php echo _l('discount'); ?></td>
                            <td><?php  echo ($affiliate_program->discount_type == 'fixed' ? app_format_money($affiliate_program->discount_percent_enjoyed, $currency->name) : $affiliate_program->discount_percent_enjoyed.'%'); ?></td>
                          </tr>
                          <?php if($affiliate_program->discount_first_invoices == 'enable'){ ?>
                          <tr class="project-overview">
                            <td class="bold" width="30%"><?php echo _l('number_first_invoices'); ?></td>
                            <td><?php  echo (isset($affiliate_program) ? $affiliate_program->discount_number_first_invoices : ''); ?></td>
                          </tr>
                          <tr class="project-overview">
                            <td class="bold" width="30%"><?php echo _l('discount_first_invoices'); ?></td>
                            <td><?php  echo ($affiliate_program->discount_type == 'fixed' ? app_format_money($affiliate_program->discount_percent_first_invoices, $currency->name) : $affiliate_program->discount_percent_first_invoices.'%'); ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table> 
                      <?php } ?>
                      <?php if($affiliate_program->discount_policy_type == 4){ ?>
                        <h5><?php echo new_html_entity_decode($discount_policy_type); ?></h5>
                         <table class="table border table-striped no-margin">
                          <thead>
                            <th><strong><?php echo _l('amount'); ?></strong></th>
                            <th><strong><?php echo _l('discount'); ?></strong></th>
                          </thead>
                        <tbody>
                          <?php 
                          $setting = json_decode($affiliate_program->discount_ladder_product_setting, true);
                          foreach ($setting as $key => $value) {
                            
                            ?>
                            <tr class="project-overview">
                              <td><strong><?php echo new_html_entity_decode(get_affiliate_product_name($key)); ?></strong></td>
                              <td></td>
                            </tr>
                            <?php foreach ($value["discount_from_amount_product"] as $k => $val) { 
                                $amount = '';
                                if($affiliate_program->discount_type == 'fixed'){
                                  $amount = app_format_money($value["discount_percent_enjoyed_ladder_product"][$k], $currency->name);
                                }else {
                                  $amount = $value["discount_percent_enjoyed_ladder_product"][$k].'%';
                                }
                              ?>
                            <tr class="project-overview">
                              <td><?php echo new_html_entity_decode(($value["discount_from_amount_product"][$k] != '' ? $value["discount_from_amount_product"][$k] : '0').' - '. ($value["discount_to_amount_product"][$k] != '' ? $value["discount_to_amount_product"][$k] : '&infin;')); ?></td>
                              <td><?php  echo new_html_entity_decode($amount); ?></td>
                            </tr>
                          <?php } ?>
                          <?php } ?>
                        </tbody>
                      </table> 
                      <?php } ?>
                      <?php if($affiliate_program->discount_policy_type == 3){ ?>
                        <h5><?php echo new_html_entity_decode($discount_policy_type); ?></h5>
                         <table class="table border table-striped no-margin">
                          <thead>
                            <th><strong><?php echo _l('product_groups'); ?></strong></th>
                            <th><strong><?php echo _l('affiliate_product'); ?></strong></th>
                            <th><strong><?php echo _l('number'); ?></strong></th>
                            <th><strong><?php echo _l('discount'); ?></strong></th>
                          </thead>
                        <tbody>
                          <?php 
                          $setting = json_decode($affiliate_program->discount_product_setting, true);
                          foreach ($setting as $key => $value) {
                            if($affiliate_program->discount_type == 'fixed'){
                              $amount = app_format_money($value[4], $currency->name);
                            }else {
                              $amount = $value[4].'%';
                            }

                            $pr_group = explode('|', $value[1]);
                            $pr_group_string = '';
                            foreach ($pr_group as $k => $val) {
                              if($pr_group_string == ''){
                                $pr_group_string .= get_affiliate_product_group_name($val);;
                              }else {
                                $pr_group_string .= ', '.get_affiliate_product_group_name($val);
                              }
                            }

                            $pr = explode('|', $value[1]);
                            $pr_string = '';
                            foreach ($pr as $k => $val) {
                              if($pr_string == ''){
                                $pr_string .= get_affiliate_product_name($val);;
                              }else {
                                $pr_string .= ', '.get_affiliate_product_name($val);
                              }
                            }
                            ?>
                            <tr class="project-overview">
                              <td><strong><?php echo new_html_entity_decode($pr_group_string); ?></strong></td>
                              <td><strong><?php echo new_html_entity_decode($pr_string); ?></strong></td>
                              <td><?php echo new_html_entity_decode(($value[2] != '' ? $value[2] : '0').' - '. ($value[3] != '' ? $value[3] : '&infin;')); ?></td>
                              <td><?php  echo new_html_entity_decode($amount); ?></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table> 
                      <?php } ?>
                      <hr>
                      <h5><?php echo _l('products_list'); ?></h5>
                        <table class="table dt-table">
                          <thead>
                            <th><?php echo _l('name'); ?></th>
                            <th><?php echo _l('rate'); ?></th>
                            <th><?php echo _l('description'); ?></th>
                          </thead>
                          <tbody>
                            <?php if(isset($affiliate_program_products['discount'])){
                              foreach ($affiliate_program_products['discount'] as $discount_product) { ?>
                                <tr>
                                  <td><a href="#" onclick="product_detail(<?php echo new_html_entity_decode($discount_product['id']); ?>); return false;"><?php echo new_html_entity_decode($discount_product['description']); ?></a></td>
                                  <td><?php echo app_format_money($discount_product["rate"], $currency->name); ?></td>
                                  <td><?php echo new_html_entity_decode($discount_product['long_description']); ?></td>
                                </tr>
                            <?php } 
                            } ?>
                          </tbody>
                        </table> 
                    </div>
                  </div>
                </div>
                <?php } ?>
                <?php if($affiliate_program->enable_commission == 'enable'){ ?>
                  <?php 
                      $commission_policy_type = '';
                      $commission_affiliate_type = '';
                      if(isset($affiliate_program)){
                        if($affiliate_program->commission_affiliate_type == 1){
                          $commission_affiliate_type = _l('product_view');
                        }elseif ($affiliate_program->commission_affiliate_type == 2) {
                          $commission_affiliate_type = _l('new_registration');
                        }elseif ($affiliate_program->commission_affiliate_type == 3) {
                          $commission_affiliate_type = _l('affiliate_product');
                        }

                        switch ($affiliate_program->commission_policy_type) {
                          case '1':
                            $commission_policy_type = _l('calculated_as_ladder');
                            break;
                          case '2':
                            $commission_policy_type = _l('calculated_as_percentage');
                            break;
                          case '3':
                            $commission_policy_type = _l('calculated_by_the_product');
                            break;
                          case '4':
                            $commission_policy_type = _l('calculated_product_as_ladder');
                            break;
                          default:
                            break;
                        }
                      } ?>
                <div class="col-md-6">
                  <div class="panel panel-info">
                    <div class="panel-heading"><?php echo _l('commission');  ?></div>
                    <div class="panel-body">
                      <table class="table border table-striped no-margin">
                    <tbody>
                      <tr class="project-overview">
                        <td class="bold" width="30%"><?php echo _l('commission_type'); ?></td>
                        <td><?php  echo ( isset($affiliate_program) ? $commission_affiliate_type : ''); ?></td>
                      </tr>
                      <?php if($affiliate_program->commission_affiliate_type == 3){ ?>
                        <tr class="project-overview">
                          <td class="bold" width="30%"><?php echo _l('commission_policy_type'); ?></td>
                          <td><?php  echo ( isset($affiliate_program) ? $commission_policy_type : ''); ?></td>
                        </tr>
                        <?php if($affiliate_program->commission_policy_type == 2){ ?>
                        <tr class="project-overview">
                            <td class="bold" width="30%"><?php echo _l('discount'); ?></td>
                            <td><?php  echo ($affiliate_program->commission_type == 'fixed' ? app_format_money($affiliate_program->commission_percent_enjoyed, $currency->name) : $affiliate_program->commission_percent_enjoyed.'%'); ?></td>
                          </tr>
                          <?php if($affiliate_program->commission_first_invoices == '1'){ ?>
                          <tr class="project-overview">
                            <td class="bold" width="30%"><?php echo _l('number_first_invoices'); ?></td>
                            <td><?php  echo (isset($affiliate_program) ? $affiliate_program->commission_number_first_invoices : ''); ?></td>
                          </tr>
                          <tr class="project-overview">
                            <td class="bold" width="30%"><?php echo _l('commission_first_invoices'); ?></td>
                            <td><?php  echo ($affiliate_program->commission_type == 'fixed' ? app_format_money($affiliate_program->commission_percent_first_invoices, $currency->name) : $affiliate_program->commission_percent_first_invoices.'%'); ?></td>
                          </tr>
                          <?php } ?>
                      <?php } ?>
                        <?php if($affiliate_program->commission_policy_type == 4){ ?>
                         <table class="table border table-striped no-margin">
                          <thead>
                            <th><strong><?php echo _l('amount'); ?></strong></th>
                            <th><strong><?php echo _l('commission'); ?></strong></th>
                          </thead>
                        <tbody>
                          <?php 
                          $setting = json_decode($affiliate_program->commission_ladder_product_setting, true);
                          foreach ($setting as $key => $value) {
                            
                            ?>
                            <tr class="project-overview">
                              <td><strong><?php echo new_html_entity_decode(get_affiliate_product_name($key)); ?></strong></td>
                              <td></td>
                            </tr>
                            <?php foreach ($value["commission_from_amount_product"] as $k => $val) { 
                                $amount = '';
                                if($affiliate_program->commission_type == 'fixed'){
                                  $amount = app_format_money($value["commission_percent_enjoyed_ladder_product"][$k], $currency->name);
                                }else {
                                  $amount = $value["commission_percent_enjoyed_ladder_product"][$k].'%';
                                }
                              ?>
                            <tr class="project-overview">
                              <td><?php echo new_html_entity_decode(($value["commission_from_amount_product"][$k] != '' ? $value["commission_from_amount_product"][$k] : '0').' - '. ($value["commission_to_amount_product"][$k] != '' ? $value["commission_to_amount_product"][$k] : '&infin;')); ?></td>
                              <td><?php  echo new_html_entity_decode($amount); ?></td>
                            </tr>
                          <?php } ?>
                          <?php } ?>
                        </tbody>
                      </table> 
                      <?php } ?>
                      <?php if($affiliate_program->commission_policy_type == 1){ ?>
                         <table class="table border table-striped no-margin">
                          <thead>
                            <th><strong><?php echo _l('amount'); ?></strong></th>
                            <th><strong><?php echo _l('commission'); ?></strong></th>
                          </thead>
                        <tbody>
                          <?php 
                           $setting = json_decode($affiliate_program->commission_ladder_setting);
                          foreach ($setting as $key => $value) {
                            $amount = '';
                            if($affiliate_program->commission_type == 'fixed'){
                              $amount = app_format_money($value->commission_percent_enjoyed_ladder, $currency->name);
                            }else {
                              $amount = $value->commission_percent_enjoyed_ladder.'%';
                            }
                            ?>
                            <tr class="project-overview">
                              <td><?php echo new_html_entity_decode(($value->commission_from_amount != '' ? $value->commission_from_amount : '0').' - '. ($value->commission_to_amount != '' ? $value->commission_to_amount : '&infin;')); ?></td>
                              <td><?php  echo new_html_entity_decode($amount); ?></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table> 
                      <?php } ?>
                      <?php }elseif ($affiliate_program->commission_affiliate_type == 2) { ?>
                        <tr class="project-overview">
                          <td class="bold" width="30%"><?php echo _l('number_of_registration'); ?></td>
                          <td><?php  echo ( isset($affiliate_program) ? $affiliate_program->commission_number_registration : ''); ?></td>
                        </tr>
                        <tr class="project-overview">
                          <td class="bold" width="30%"><?php echo _l('commission'); ?></td>
                          <td><?php  echo ( isset($affiliate_program) ? app_format_money($affiliate_program->commission_of_registration, $currency->name) : ''); ?></td>
                        </tr>
                        <tr class="project-overview">
                          <td class="bold" width="30%"><?php echo _l('public_link'); ?></td>
                          <td>
                            <div class="row">
                              <div class="pull-right _buttons mright5">
                                <a href="javascript:void(0)" onclick="copy_public_link(); return false;" class="btn btn-warning btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('copy_public_link'); ?>" data-placement="bottom"><i class="fa fa-clone "></i></a>
                              </div>
                              <div class="col-md-9">
                                <?php echo render_input('link_register','', site_url('affiliate/authentication_affiliate/register?referral_code='.get_affiliate_user_code())); ?>
                             </div>
                            </div>
                           </td>
                       </tr>
                      <?php }elseif ($affiliate_program->commission_affiliate_type == 1) { ?>
                        <tr class="project-overview">
                          <td class="bold" width="30%"><?php echo _l('number_of_view'); ?></td>
                          <td><?php  echo ( isset($affiliate_program) ? $affiliate_program->commission_number_view : ''); ?></td>
                        </tr>
                        <tr class="project-overview">
                          <td class="bold" width="30%"><?php echo _l('commission'); ?></td>
                          <td><?php  echo ( isset($affiliate_program) ? app_format_money($affiliate_program->commission_of_view, $currency->name) : ''); ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                    <?php if($affiliate_program->commission_affiliate_type == '1'){ ?>
                    <hr>
                    <h5><?php echo _l('products_list'); ?></h5>
                        <table class="table dt-table">
                          <thead>
                            <th><?php echo _l('name'); ?></th>
                            <th><?php echo _l('rate'); ?></th>
                            <th><?php echo _l('description'); ?></th>
                          </thead>
                          <tbody>
                            <?php if(isset($affiliate_program_products['commission'])){
                              foreach ($affiliate_program_products['commission'] as $discount_product) { ?>
                                <tr>
                                  <td><a href="#" onclick="product_detail(<?php echo new_html_entity_decode($discount_product['id']); ?>,<?php echo new_html_entity_decode($affiliate_program->id); ?>); return false;"><?php echo new_html_entity_decode($discount_product['description']); ?></a></td>
                                  <td><?php echo app_format_money($discount_product["rate"], $currency->name); ?></td>
                                  <td><?php echo new_html_entity_decode($discount_product['long_description']); ?></td>
                                </tr>
                            <?php } 
                            } ?>
                          </tbody>
                        </table> 
                    </div>
                  </div>
                    <?php } ?>
                  <?php } ?>
                  <?php if($affiliate_program->commission_affiliate_type == '3'){ ?>
                  <hr>
                    <h5><?php echo _l('products_list'); ?></h5>
                        <table class="table dt-table">
                          <thead>
                            <th><?php echo _l('name'); ?></th>
                            <th><?php echo _l('rate'); ?></th>
                            <th><?php echo _l('description'); ?></th>
                          </thead>
                          <tbody>
                            <?php if(isset($affiliate_program_products['commission'])){
                              foreach ($affiliate_program_products['commission'] as $discount_product) { ?>
                                <tr>
                                  <td><a href="#" onclick="product_detail(<?php echo new_html_entity_decode($discount_product['id']); ?>); return false;"><?php echo new_html_entity_decode($discount_product['description']); ?></a></td>
                                  <td><?php echo app_format_money($discount_product["rate"], $currency->name); ?></td>
                                  <td><?php echo new_html_entity_decode($discount_product['long_description']); ?></td>
                                </tr>
                            <?php } 
                            } ?>
                          </tbody>
                        </table> 
                    </div>
                  </div>
                <?php } ?>
                </div>
            </div>
          </div>
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
