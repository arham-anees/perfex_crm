<?php init_head();?>
<div id="wrapper" class="commission">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <?php $arrAtt = array();
                $arrAtt['data-type']='currency'; ?>
          <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'commission-policy-form','autocomplete'=>'off')); ?>
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <div class="row">

            <div class="col-md-12">
              <?php $value = (isset($affiliate_program) ? $affiliate_program->name : ''); ?>
              <?php echo render_input('name','name',$value,'text'); ?>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <?php $value = (isset($affiliate_program) ? $affiliate_program->from_date : ''); ?>
                  <?php echo render_date_input('from_date','from_date',$value); ?>
                </div>
                <div class="col-md-6">
                  <?php $value = (isset($affiliate_program) ? $affiliate_program->to_date : ''); ?>
                  <?php echo render_date_input('to_date','to_date',$value); ?>
                </div>
              </div>
            </div>
            <div class="col-md-12">
            <?php
                $prioritys = [0 => ['id' => '1', 'name' => '1'],
                              1 => ['id' => '2', 'name' => '2'],
                              2 => ['id' => '3', 'name' => '3'],
                              3 => ['id' => '4', 'name' => '4'],
                              4 => ['id' => '5', 'name' => '5']];
                $selected = (isset($affiliate_program) ? explode(',', $affiliate_program->priority) : ''); 
                echo render_select('priority',$prioritys,array('id','name'),'priority',$selected,array(),array(),'','',false); ?>
            </div>
            <div class="col-md-12">
              <?php
                $selected = (isset($affiliate_program) ? explode(',', $affiliate_program->category) : ''); 
              echo render_select('category',$program_categorys,array('id','name'),'program_category',$selected); ?>
            </div>
            
            <div class="col-md-12">
              <div class="horizontal-scrollable-tabs preview-tabs-top">
               <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
               <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
               <div class="horizontal-tabs">
                  <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                     <li role="presentation" class="active">
                        <a href="#tab_discount" aria-controls="tab_discount" role="tab" data-toggle="tab">
                        <?php echo _l('discount'); ?>
                        </a>
                     </li>
                     <li role="presentation">
                        <a href="#tab_commission" aria-controls="tab_commission" role="tab" data-toggle="tab">
                        <?php echo _l('commission'); ?>
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
          </div>
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tab_discount">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="enable_discount" id="enable_discount" value="enable" <?php if(isset($affiliate_program) && $affiliate_program->enable_discount == 'enable'){echo 'checked';} ?>>
                    <label for="enable_discount"><?php echo _l('enable'); ?></label>
                  </div>
                </div>
              </div>
              <div id="div_discount" class="col-md-12 <?php if(isset($affiliate_program) && $affiliate_program->enable_discount == 'enable'){echo '';}else{ echo 'hide';} ?>">
              <div class="col-md-12">
                <div class="form-group">
                      <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="discount_enable_customer" id="discount_enable_customer" value="enable" <?php if(isset($affiliate_program) && $affiliate_program->discount_enable_customer == 'enable'){echo 'checked';} ?>>
                        <label for="discount_enable_customer"><?php echo _l('client'); ?></label>
                      </div>
                </div>
              </div>
              <div id="div_discount_client" class="col-md-12 <?php if(isset($affiliate_program) && $affiliate_program->discount_enable_customer == 'enable'){echo '';}else{ echo 'hide';} ?>">
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',', $affiliate_program->discount_customer_groups) : ''); 
                      echo render_select('discount_customer_groups[]',$client_groups,array('id','name'),'client_groups',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',',$affiliate_program->discount_customers) : '');
                      echo render_select('discount_customers[]',$clients,array('userid','company','customerGroups'),'clients',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                      <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="discount_enable_product" id="discount_enable_product" value="enable" <?php if(isset($affiliate_program) && $affiliate_program->discount_enable_product == 'enable'){echo 'checked';} ?>>
                        <label for="discount_enable_product"><?php echo _l('affiliate_product'); ?></label>
                      </div>
                </div>
              </div>
              <div id="div_discount_product" class="col-md-12 <?php if(isset($affiliate_program) && $affiliate_program->discount_enable_product == 'enable'){echo '';}else{ echo 'hide';} ?>">
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',', $affiliate_program->discount_product_groups) : ''); 
                      echo render_select('discount_product_groups[]',$product_groups,array('id','label'),'product_groups',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',',$affiliate_program->discount_products) : '');
                      echo render_select('discount_products[]',$products,array('id','label'),'affiliate_product',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                      <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="discount_enable_member" id="discount_enable_member" value="enable" <?php if(isset($affiliate_program) && $affiliate_program->discount_enable_member == 'enable'){echo 'checked';} ?>>
                        <label for="discount_enable_member"><?php echo _l('member'); ?></label>
                      </div>
                </div>
              </div>
              <div id="div_discount_member" class="col-md-12 <?php if(isset($affiliate_program) && $affiliate_program->discount_enable_member == 'enable'){echo '';}else{echo 'hide';} ?>">
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',', $affiliate_program->discount_member_groups) : ''); 
                      echo render_select('discount_member_groups[]',$member_groups,array('id','name'),'member_group',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',',$affiliate_program->discount_members) : '');
                      echo render_select('discount_members[]',$members,array('id','firstname','lastname'),'member',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
              </div>
            <?php $selected = (isset($affiliate_program) ? $affiliate_program->discount_type : 'percentage'); ?>
            <div class="form-group col-md-12">
              <label for="discount_type"><?php echo _l('discount_type'); ?></label><br />
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="discount_type" id="discount_type_percentage" value="percentage" <?php if($selected == 'percentage'){echo 'checked';} ?>>
                <label for="discount_type_percentage"><?php echo _l("percentage"); ?></label>
              </div>
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="discount_type" id="discount_type_fixed" value="fixed" <?php if($selected == 'fixed'){echo 'checked';} ?>>
                <label for="discount_type_fixed"><?php echo _l("fixed"); ?></label>
              </div>
            </div>
            <?php $selected = (isset($affiliate_program) ? $affiliate_program->discount_amount_to_calculate : 'total_invoice_amount'); 
                  $disabled = affiliate_get_status_modules_all('warehouse') ? '' : "disabled";
            ?>
            <div class="form-group col-md-12">
              <label for="discount_amount_to_calculate"><?php echo _l('amount_to_calculate'); ?></label><br />
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="discount_amount_to_calculate" id="discount_total_invoice_amount" value="total_invoice_amount" <?php if($selected == 'total_invoice_amount'){echo 'checked';} ?>>
                <label for="discount_total_invoice_amount"><?php echo _l("total_invoice_amount"); ?></label>
              </div>
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="discount_amount_to_calculate" id="discount_profit" value="profit" <?php if($selected == 'profit'){echo 'checked';} echo new_html_entity_decode($disabled); ?>>
                <label for="discount_profit"><?php echo _l("profit"); ?> <i class="fa fa-question-circle" data-toggle="tooltip" title="" data-original-title="<?php echo _l('profit_note_tooltip'); ?>"></i></label>
              </div>
            </div>
            
            <div class="col-md-12">
              <?php $affiliate_program_type = [ 0 => ['id' => '1', 'name' => _l('calculated_as_ladder')],
                                              1 => ['id' => '2', 'name' => _l('calculated_as_percentage')],
                                              2 => ['id' => '3', 'name' => _l('calculated_by_the_product')],
                                              3 => ['id' => '4', 'name' => _l('calculated_product_as_ladder')]];
                $value = (isset($affiliate_program) ? $affiliate_program->discount_policy_type : '');                      
              echo render_select('discount_policy_type', $affiliate_program_type,array('id','name'),'discount_policy_type', $value); ?>
            </div>
          <div class="<?php if(isset($affiliate_program) && $affiliate_program->discount_policy_type == '1'){ echo '';}else{echo 'hide';}?>" id = "discount_calculated_as_ladder">
            <div class="col-md-12">
              <div class="row discount_list_ladder_setting">
                <?php if(!isset($affiliate_program)) { ?>
                <div id="discount_item_ladder_setting">
                  <div class="row">
                    <div class="col-md-11">
                      <div class="col-md-4">
                        <?php echo render_input('discount_from_amount[0]','from_amount','','text', $arrAtt); ?>
                      </div>
                      <div class="col-md-4">
                        <?php echo render_input('discount_to_amount[0]','to_amount','','text', $arrAtt); ?>
                      </div>
                      <div class="col-md-4">
                        <?php echo render_input('discount_percent_enjoyed_ladder[0]','discount','','number', array('min' => 0)); ?>
                      </div>
                    </div>
                    <div class="col-md-1 no-padding">
                    <span class="pull-bot">
                        <button name="add" class="btn new_discount_item_ladder btn-success mtop25" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                    </div>
                  </div>
                </div>
                <?php }else{ 
                  $setting = json_decode($affiliate_program->discount_ladder_setting);
                  ?>
                  <?php foreach ($setting as $key => $value) { ?>
                  <div id="discount_item_ladder_setting">
                    <div class="row">
                      <div class="col-md-11">
                        <div class="col-md-4">
                        <?php echo render_input('discount_from_amount['.$key.']','from_amount',$value->discount_from_amount,'text',$arrAtt); ?>
                      </div>
                      <div class="col-md-4">
                        <?php echo render_input('discount_to_amount['.$key.']','to_amount',$value->discount_to_amount,'text',$arrAtt); ?>
                      </div>
                      <div class="col-md-4" id="is_staff_0">
                        <?php echo render_input('discount_percent_enjoyed_ladder['.$key.']','discount',$value->discount_percent_enjoyed_ladder,'text',$arrAtt); ?>
                      </div>
                      </div>
                      <div class="col-md-1">
                      <span class="pull-bot">
                          <?php if($key != 0){ ?>
                            <button name="add" class="btn remove_item_ladder btn-danger mtop25" data-ticket="true" type="button"><i class="fa fa-minus"></i></button>
                          <?php }else{ ?>
                            <button name="add" class="btn new_discount_item_ladder btn-success mtop25" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                          <?php } ?>
                            </span>
                      </div>
                    </div>
                  </div>
                  <?php }
                  } ?>
              </div>
            </div>
          </div>
          <div class="<?php if(isset($affiliate_program) && $affiliate_program->discount_policy_type == '2'){ echo '';}else{echo 'hide';}?>" id="discount_calculated_as_percentage">
            <div class="col-md-12">
              <?php $value = (isset($affiliate_program) ? $affiliate_program->discount_percent_enjoyed : ''); ?>
              <?php echo render_input('discount_percent_enjoyed','discount',$value,'number',array('min' => 0)); ?>

              <div class="form-group">
                <div class="checkbox checkbox-primary">
                  <input type="checkbox" name="discount_first_invoices" id="discount_first_invoices" value="enable" <?php if(isset($affiliate_program) && $affiliate_program->discount_first_invoices == 'enable'){ echo 'checked';}?>>
                  <label for="discount_first_invoices"><?php echo _l('discount_first_invoices'); ?></label>
                </div>
              </div>
              <div id="div_discount_first_invoices" class="<?php if(isset($affiliate_program) && $affiliate_program->discount_first_invoices == 'enable'){ echo '';}else{echo 'hide';}?>">
                <?php $value = (isset($affiliate_program) ? $affiliate_program->discount_number_first_invoices : ''); ?>
                <?php echo render_input('discount_number_first_invoices','number_first_invoices',$value,'number',array('min' => 0)); ?>
                <?php $value = (isset($affiliate_program) ? $affiliate_program->discount_percent_first_invoices : ''); ?>
                <?php echo render_input('discount_percent_first_invoices','discount_first_invoices',$value,'number',array('min' => 0)); ?>
              </div>
            </div>
            <div>
            </div>
          </div>
          <div class="<?php if(isset($affiliate_program) && $affiliate_program->discount_policy_type == '3'){ echo '';}else{echo 'is_hide';}?>" id="discount_calculated_by_the_product">
            <div class="col-md-12">
              <h4 class="font-bold"><?php echo _l('calculated_by_the_product'); ?></h4>
                <div id="discount_product_setting" class="mbot10"></div>
              <?php echo form_hidden('discount_product_setting'); ?>
              <?php 
                    if(isset($affiliate_program) && $affiliate_program->discount_policy_type == '3'){
                      $discount_product_setting = json_decode($affiliate_program->discount_product_setting);
                      $financial_col = ['product_groups','affiliate_product','number_from','number_to','percent'];
                      foreach ($discount_product_setting as $key => $value) {
                            $discount_product_setting[$key] = array_combine($financial_col, $value);
                      }
                      $discount_product_setting = json_encode($discount_product_setting);
                    }else{
                      $discount_product_setting = '[[]]';
                    }
              ?>
            </div>
          </div>
          <div class="<?php if(isset($affiliate_program) && $affiliate_program->discount_policy_type == '4'){ echo '';}else{echo 'hide';}?>" id = "discount_calculated_product_as_ladder">
            <div class="col-md-12">
              <div id="discount_task_checklist_category">
                <?php if(isset($affiliate_program)){
                  $setting = json_decode($affiliate_program->discount_ladder_product_setting, true);
                  $i = 0;
                   foreach ($setting as $key => $value) { 
                    ?>
                      <div class="discount_template_children">
                        <div class="col-md-12">
                          <hr>
                        </div>
                        <?php echo render_select('discount_ladder_product['.$i.']', $products,array('id','label'),'affiliate_product', $key); ?>
                      <div class="template" value="<?php echo new_html_entity_decode($i); ?>">
                        <?php foreach ($value["discount_from_amount_product"] as $k => $val) {
                          ?>
                        <div class="row" id="template-item">
                          <div class="col-md-1">
                          </div>
                          <div class="col-md-10">
                            <div class="col-md-4">
                              <?php echo render_input('discount_from_amount_product['.$i.']['.$k.']','from_amount',$val,'text', $arrAtt); ?>
                            </div>
                            <div class="col-md-4">
                              <?php echo render_input('discount_to_amount_product['.$i.']['.$k.']','to_amount',$value["discount_to_amount_product"][$k],'text', $arrAtt); ?>
                            </div>
                            <div class="col-md-4">
                              <?php echo render_input('discount_percent_enjoyed_ladder_product['.$i.']['.$k.']','discount',$value["discount_percent_enjoyed_ladder_product"][$k],'number', array('min' => 0)); ?>
                            </div>
                          </div>
                          <div class="col-md-1">
                            <span class="input-group-btn mtop25 pull-left">
                              <button name="add" class="btn <?php if($k == 0){ echo 'new_discount_template_item btn-success'; }else{ echo 'remove_template_item btn-danger';} ?>" data-ticket="true" type="button"><i class="fa <?php if($k == 0){ echo 'fa-plus'; }else{ echo 'fa-minus';} ?>"></i></button>
                            </span>
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                      <div class="col-md-12">
                        <span class="input-group-btn">
                          <button name="add_template" class="btn <?php if($i == 0){ echo 'new_discount_template btn-success'; }else{ echo 'remove_template btn-danger';} ?>" data-ticket="true" type="button"><i class="fa <?php if($i == 0){ echo 'fa-plus'; }else{ echo 'fa-minus';} ?>"></i></button>
                        </span>
                        <br>
                      </div>
                    </div>
                  <?php 
                    $i++;
                  }
              }else{ ?>
                <div class="discount_template_children">
                  <div class="col-md-12">
                    <hr>
                  </div>
                  <?php echo render_select('discount_ladder_product[0]', $products,array('id','label'),'affiliate_product'); ?>
                  <div class="template" value="0">
                    <div class="row" id="template-item">
                      <div class="col-md-1">
                      </div>
                      <div class="col-md-10">
                        <div class="col-md-4">
                            <?php echo render_input('discount_from_amount_product[0][0]','from_amount','','text', $arrAtt); ?>
                          </div>
                          <div class="col-md-4">
                            <?php echo render_input('discount_to_amount_product[0][0]','to_amount','','text', $arrAtt); ?>
                          </div>
                          <div class="col-md-4">
                            <?php echo render_input('discount_percent_enjoyed_ladder_product[0][0]','discount','','number', array('min' => 0)); ?>
                          </div>
                      </div>
                      <div class="col-md-1">
                        <span class="input-group-btn mtop25 pull-left">
                          <button name="add" class="btn new_discount_template_item btn-success" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <span class="input-group-btn">
                      <button name="add_template" class="btn new_discount_template btn-success" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                    </span>
                  <br>
                  </div>
                </div>
              <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
        <div role="tabpanel" class="tab-pane" id="tab_commission">
          <div class="col-md-12">
            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input type="checkbox" name="enable_commission" id="enable_commission" value="enable" <?php if(isset($affiliate_program) && $affiliate_program->enable_commission == 'enable'){echo 'checked';} ?>>
                <label for="enable_commission"><?php echo _l('enable'); ?></label>
              </div>
            </div>
          </div>
          <div id="div_commission" class="col-md-12 <?php if(isset($affiliate_program) && $affiliate_program->enable_commission == 'enable'){echo '';}else{echo 'hide';} ?>">
            <div class="col-md-12">
              <?php $commission_affiliate_type = [ 0 => ['id' => '1', 'name' => _l('product_view')],
                                              1 => ['id' => '2', 'name' => _l('new_registration')],
                                              2 => ['id' => '3', 'name' => _l('affiliate_product')]];
                $value = (isset($affiliate_program) ? $affiliate_program->commission_affiliate_type : '');                      
                echo render_select('commission_affiliate_type', $commission_affiliate_type,array('id','name'),'affiliate_type', $value);
                 ?>
            </div>
            <div id="div_client" class="<?php if(isset($affiliate_program) && $affiliate_program->commission_affiliate_type == '3'){echo '';}else{echo 'hide';} ?>">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="commission_enable_customer" id="commission_enable_customer" value="enable" <?php if(isset($affiliate_program) && $affiliate_program->commission_enable_customer == 'enable'){echo 'checked';} ?>>
                    <label for="commission_enable_customer"><?php echo _l('client'); ?></label>
                  </div>
                </div>
              </div>
              <div id="div_client_children" class="col-md-12 <?php if(isset($affiliate_program) && $affiliate_program->commission_enable_customer == 'enable'){echo '';}else{echo 'hide';} ?>">
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',', $affiliate_program->commission_customer_groups) : ''); 
                      echo render_select('commission_customer_groups[]',$client_groups,array('id','name'),'client_groups',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',',$affiliate_program->commission_customers) : '');
                      echo render_select('commission_customers[]',$clients,array('userid','company','customerGroups'),'clients',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
              </div>
            </div>
            <div id="div_product" class="<?php if(isset($affiliate_program) && ($affiliate_program->commission_affiliate_type == '1' || $affiliate_program->commission_affiliate_type == '3')){echo '';}else{echo 'hide';} ?>">
              <div class="col-md-12">
                <div class="form-group">
                      <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="commission_enable_product" id="commission_enable_product" value="enable" <?php if(isset($affiliate_program) && $affiliate_program->commission_enable_product == 'enable'){echo 'checked';} ?>>
                        <label for="commission_enable_product"><?php echo _l('affiliate_product'); ?></label>
                      </div>
                </div>
              </div>
              <div id="div_product_children" class="col-md-12 <?php if(isset($affiliate_program) && $affiliate_program->commission_enable_product == 'enable'){echo '';}else{echo 'hide';} ?>">
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',', $affiliate_program->commission_product_groups) : ''); 
                      echo render_select('commission_product_groups[]',$product_groups,array('id','label'),'product_groups',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',',$affiliate_program->commission_products) : '');
                      echo render_select('commission_products[]',$products,array('id','label'),'affiliate_product',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
              </div>
            </div>
            <div id="div_member" class="<?php if(isset($affiliate_program) && $affiliate_program->commission_affiliate_type == '3'){echo '';}else{echo 'hide';} ?>">
              <div class="col-md-12">
                <div class="form-group">
                      <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="commission_enable_member" id="commission_enable_member" value="enable" <?php if(isset($affiliate_program) && $affiliate_program->commission_enable_member == 'enable'){echo 'checked';} ?>>
                        <label for="commission_enable_member"><?php echo _l('member'); ?></label>
                      </div>
                </div>
              </div>
              <div id="div_member_children" class="col-md-12 <?php if(isset($affiliate_program) && $affiliate_program->commission_enable_member == 'enable'){echo '';}else{echo 'hide';} ?>">
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',', $affiliate_program->commission_member_groups) : ''); 
                      echo render_select('commission_member_groups[]',$member_groups,array('id','name'),'member_group',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <?php
                      $selected = (isset($affiliate_program) ? explode(',',$affiliate_program->commission_members) : '');
                      echo render_select('commission_members[]',$members,array('id','firstname','lastname'),'member',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
                </div>
              </div>
            </div>
            <?php $selected = (isset($affiliate_program) ? $affiliate_program->commission_type : 'percentage'); ?>
            <div id="div_product_view_type" class="<?php if(isset($affiliate_program) && $affiliate_program->commission_affiliate_type == '1'){echo '';}else{echo 'hide';} ?>">
            <div class="col-md-12">
              <?php $value = (isset($affiliate_program) ? $affiliate_program->commission_number_view : ''); ?>
              <?php echo render_input('commission_number_view','number_of_view',$value,'number',array('min' => 0)); ?>
              <?php $value = (isset($affiliate_program) ? $affiliate_program->commission_of_view : ''); ?>
              <?php echo render_input('commission_of_view','commission',$value,'number',array('min' => 0)); ?>
            </div>
          </div>
          <div id="div_registration_type" class="<?php if(isset($affiliate_program) && $affiliate_program->commission_affiliate_type == '2'){echo '';}else{echo 'hide';} ?>">
            <div class="col-md-12">
              <?php $value = (isset($affiliate_program) ? $affiliate_program->commission_number_registration : ''); ?>
              <?php echo render_input('commission_number_registration','number_of_registration',$value,'number',array('min' => 0)); ?>
              <?php $value = (isset($affiliate_program) ? $affiliate_program->commission_of_registration : ''); ?>
              <?php echo render_input('commission_of_registration','commission',$value,'number',array('min' => 0)); ?>
            </div>
          </div>
          <div id="div_product_type" class="<?php if(isset($affiliate_program) && $affiliate_program->commission_affiliate_type == '3'){echo '';}else{echo 'hide';} ?>">
            <div class="form-group col-md-12">
              <?php $selected = (isset($affiliate_program) ? $affiliate_program->commission_type : ''); ?>
              <label for="commission_type"><?php echo _l('commission_type'); ?></label><br />
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="commission_type" id="commission_type_percentage" value="percentage" <?php if($selected == 'percentage' || $selected == ''){echo 'checked';} ?>>
                <label for="commission_type_percentage"><?php echo _l("percentage"); ?></label>
              </div>
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="commission_type" id="commission_type_fixed" value="fixed" <?php if($selected == 'fixed'){echo 'checked';} ?>>
                <label for="commission_type_fixed"><?php echo _l("fixed"); ?></label>
              </div>
            </div>
            <?php $selected = (isset($affiliate_program) ? $affiliate_program->commission_amount_to_calculate : 'total_invoice_amount'); 
                  $disabled = affiliate_get_status_modules_all('warehouse') ? '' : "disabled";
            ?>
            <div class="form-group col-md-12">
              <label for="amount_to_calculate"><?php echo _l('amount_to_calculate'); ?></label><br />
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="commission_amount_to_calculate" id="commission_total_invoice_amount" value="total_invoice_amount" <?php if($selected == 'total_invoice_amount'){echo 'checked';} ?>>
                <label for="commission_total_invoice_amount"><?php echo _l("total_invoice_amount"); ?></label>
              </div>
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="commission_amount_to_calculate" id="commission_profit" value="profit" <?php if($selected == 'profit'){echo 'checked';} echo new_html_entity_decode($disabled); ?>>
                <label for="commission_profit"><?php echo _l("profit"); ?> <i class="fa fa-question-circle" data-toggle="tooltip" title="" data-original-title="<?php echo _l('profit_note_tooltip'); ?>"></i></label>
              </div>
            </div>
            <div class="col-md-12">
              <?php $affiliate_program_type = [ 0 => ['id' => '1', 'name' => _l('calculated_as_ladder')],
                                              1 => ['id' => '2', 'name' => _l('calculated_as_percentage')],
                                              2 => ['id' => '3', 'name' => _l('calculated_by_the_product')],
                                              3 => ['id' => '4', 'name' => _l('calculated_product_as_ladder')]];
                $value = (isset($affiliate_program) ? $affiliate_program->commission_policy_type : '');                      
              echo render_select('commission_policy_type', $affiliate_program_type,array('id','name'),'commission_policy_type', $value); ?>
            </div>
          <div class="<?php if(isset($affiliate_program) && $affiliate_program->commission_policy_type == '1'){ echo '';}else{echo 'hide';}?>" id = "calculated_as_ladder">
            <div class="col-md-12">
              <div class="row list_ladder_setting">
                <?php if(!isset($affiliate_program)) { ?>
                <div id="item_ladder_setting">
                  <div class="row">
                    <div class="col-md-11">
                      <div class="col-md-4">
                        <?php echo render_input('commission_from_amount[0]','from_amount','','text', $arrAtt); ?>
                      </div>
                      <div class="col-md-4">
                        <?php echo render_input('commission_to_amount[0]','to_amount','','text', $arrAtt); ?>
                      </div>
                      <div class="col-md-4">
                        <?php echo render_input('commission_percent_enjoyed_ladder[0]','commission','','number', array('min' => 0)); ?>
                      </div>
                    </div>
                    <div class="col-md-1 no-padding">
                    <span class="pull-bot">
                        <button name="add" class="btn new_item_ladder btn-success mtop25" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                    </div>
                  </div>
                </div>
                <?php }else{ 
                  $setting = json_decode($affiliate_program->commission_ladder_setting);
                  ?>
                  <?php foreach ($setting as $key => $value) { ?>
                  <div id="item_ladder_setting">
                    <div class="row">
                      <div class="col-md-11">
                        <div class="col-md-4">
                        <?php echo render_input('commission_from_amount['.$key.']','from_amount',$value->commission_from_amount,'text',$arrAtt); ?>
                      </div>
                      <div class="col-md-4">
                        <?php echo render_input('commission_to_amount['.$key.']','to_amount',$value->commission_to_amount,'text',$arrAtt); ?>
                      </div>
                      <div class="col-md-4" id="is_staff_0">
                        <?php echo render_input('commission_percent_enjoyed_ladder['.$key.']','commission',$value->commission_percent_enjoyed_ladder,'text',$arrAtt); ?>
                      </div>
                      </div>
                      <div class="col-md-1">
                      <span class="pull-bot">
                          <?php if($key != 0){ ?>
                            <button name="add" class="btn remove_item_ladder btn-danger mtop25" data-ticket="true" type="button"><i class="fa fa-minus"></i></button>
                          <?php }else{ ?>
                            <button name="add" class="btn new_item_ladder btn-success mtop25" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                          <?php } ?>
                            </span>
                      </div>
                    </div>
                  </div>
                  <?php }
                  } ?>
              </div>
            </div>
          </div>
          <div class="<?php if(isset($affiliate_program) && $affiliate_program->commission_policy_type == '2'){ echo '';}else{echo 'hide';}?>" id="calculated_as_percentage">
            <div class="col-md-12">
              <?php $value = (isset($affiliate_program) ? $affiliate_program->commission_percent_enjoyed : ''); ?>
              <?php echo render_input('commission_percent_enjoyed','commission',$value,'number',array('min' => 0)); ?>

              <div class="form-group">
                <div class="checkbox checkbox-primary">
                  <input type="checkbox" name="commission_first_invoices" id="commission_first_invoices" value="1" <?php if(isset($affiliate_program) && $affiliate_program->commission_first_invoices == '1'){ echo 'checked';}?>>
                  <label for="commission_first_invoices"><?php echo _l('commission_first_invoices'); ?></label>
                </div>
              </div>
              <div id="div_commission_first_invoices" class="<?php if(isset($affiliate_program) && $affiliate_program->commission_first_invoices == '1'){ echo '';}else{echo 'hide';}?>">
                <?php $value = (isset($affiliate_program) ? $affiliate_program->commission_number_first_invoices : ''); ?>
                <?php echo render_input('commission_number_first_invoices','number_first_invoices',$value,'number',array('min' => 0)); ?>
                <?php $value = (isset($affiliate_program) ? $affiliate_program->commission_percent_first_invoices : ''); ?>
                <?php echo render_input('commission_percent_first_invoices','commission_first_invoices',$value,'number',array('min' => 0)); ?>
              </div>
            </div>
            <div>
            </div>
          </div>
          <div class="<?php if(isset($affiliate_program) && $affiliate_program->commission_policy_type == '3'){ echo '';}else{echo 'is_hide';}?>" id="calculated_by_the_product">
            <div class="col-md-12">
              <h4 class="font-bold"><?php echo _l('calculated_by_the_product'); ?></h4>
                <div id="commission_product_setting" class="mbot10"></div>
              <?php echo form_hidden('commission_product_setting'); ?>
              <?php 
                    if(isset($affiliate_program) && $affiliate_program->commission_policy_type == '3'){
                      $commission_product_setting = json_decode($affiliate_program->commission_product_setting);
                      $financial_col = ['product_groups','affiliate_product','number_from','number_to','percent'];
                      foreach ($commission_product_setting as $key => $value) {
                            $commission_product_setting[$key] = array_combine($financial_col, $value);
                      }
                      $commission_product_setting = json_encode($commission_product_setting);
                    }else{
                      $commission_product_setting = '[[]]';
                    }
              ?>
            </div>
          </div>
          <div class="<?php if(isset($affiliate_program) && $affiliate_program->commission_policy_type == '4'){ echo '';}else{echo 'hide';}?>" id = "calculated_product_as_ladder">
            <div class="col-md-12">
              <div id="task_checklist_category">
                <?php if(isset($affiliate_program)){
                  $setting = json_decode($affiliate_program->commission_ladder_product_setting, true);
                  $i = 0;
                   foreach ($setting as $key => $value) { 
                    ?>
                      <div class="template_children">
                        <div class="col-md-12">
                          <hr>
                        </div>
                        <?php echo render_select('commission_ladder_product['.$i.']', $products,array('id','label'),'affiliate_product', $key); ?>
                      <div class="template" value="<?php echo new_html_entity_decode($i); ?>">
                        <?php foreach ($value["commission_from_amount_product"] as $k => $val) {
                          ?>
                        <div class="row" id="template-item">
                          <div class="col-md-1">
                          </div>
                          <div class="col-md-10">
                            <div class="col-md-4">
                                <?php echo render_input('commission_from_amount_product['.$i.']['.$k.']','from_amount',$val,'text', $arrAtt); ?>
                              </div>
                              <div class="col-md-4">
                                <?php echo render_input('commission_to_amount_product['.$i.']['.$k.']','to_amount',$value["commission_to_amount_product"][$k],'text', $arrAtt); ?>
                              </div>
                              <div class="col-md-4">
                                <?php echo render_input('commission_percent_enjoyed_ladder_product['.$i.']['.$k.']','commission',$value["commission_percent_enjoyed_ladder_product"][$k],'number', array('min' => 0)); ?>
                              </div>
                          </div>
                          <div class="col-md-1">
                            <span class="input-group-btn mtop25 pull-left">
                              <button name="add" class="btn <?php if($k == 0){ echo 'new_template_item btn-success'; }else{ echo 'remove_template_item btn-danger';} ?>" data-ticket="true" type="button"><i class="fa <?php if($k == 0){ echo 'fa-plus'; }else{ echo 'fa-minus';} ?>"></i></button>
                            </span>
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                      <div class="col-md-12">
                        <span class="input-group-btn">
                          <button name="add_template" class="btn <?php if($i == 0){ echo 'new_template btn-success'; }else{ echo 'remove_template btn-danger';} ?>" data-ticket="true" type="button"><i class="fa <?php if($i == 0){ echo 'fa-plus'; }else{ echo 'fa-minus';} ?>"></i></button>
                        </span>
                        <br>
                      </div>
                    </div>
                  <?php 
                    $i++;
                  }
              }else{ ?>
                <div class="template_children">
                  <div class="col-md-12">
                    <hr>
                  </div>
                  <?php echo render_select('commission_ladder_product[0]', $products,array('id','label'),'affiliate_product'); ?>
                  <div class="template" value="0">
                    <div class="row" id="template-item">
                      <div class="col-md-1">
                      </div>
                      <div class="col-md-10">
                        <div class="col-md-4">
                            <?php echo render_input('commission_from_amount_product[0][0]','from_amount','','text', $arrAtt); ?>
                          </div>
                          <div class="col-md-4">
                            <?php echo render_input('commission_to_amount_product[0][0]','to_amount','','text', $arrAtt); ?>
                          </div>
                          <div class="col-md-4">
                            <?php echo render_input('commission_percent_enjoyed_ladder_product[0][0]','commission','','number', array('min' => 0)); ?>
                          </div>
                      </div>
                      <div class="col-md-1">
                        <span class="input-group-btn mtop25 pull-left">
                          <button name="add" class="btn new_template_item btn-success" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <span class="input-group-btn">
                      <button name="add_template" class="btn new_template btn-success" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                    </span>
                  <br>
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
          <div class="row">
            <div class="col-md-12">    
              <div class="modal-footer">
                <button type="submit" class="btn btn-info commission-policy-form-submiter"><?php echo _l('submit'); ?></button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/affiliate/assets/js/affiliate_program_js.php';?>
