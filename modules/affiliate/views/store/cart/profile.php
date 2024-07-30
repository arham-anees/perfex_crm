<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo _l('client_add_edit_profile'); ?></h4>
<div class="row">
   <?php echo form_open($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
   <div class="additional"></div>
   <div class="col-md-12">
      <div class="horizontal-scrollable-tabs">
         <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
               <li role="presentation" class="<?php if(!$this->input->get('tab')){echo 'active';}; ?>">
                  <a href="#contact_info" aria-controls="contact_info" role="tab" data-toggle="tab">
                     <?php echo _l( 'customer_profile_details'); ?>
                  </a>
               </li>
               <li role="presentation">
                  <a href="#billing_and_shipping" aria-controls="billing_and_shipping" role="tab" data-toggle="tab">
                     <?php echo _l( 'billing_shipping'); ?>
                  </a>
               </li>              
            </ul>
         </div>
      </div>
      <div class="tab-content">

         <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab')){echo ' active';}; ?>" id="contact_info">
            <div class="row">
               <div class="col-md-12 mtop15 <?php if(isset($client) && (!is_empty_customer_company($client->userid) && total_rows(db_prefix().'contacts',array('userid'=>$client->userid,'is_primary'=>1)) > 0)) { echo ''; } else {echo ' hide';} ?>" id="client-show-primary-contact-wrapper">
                  <div class="checkbox checkbox-info mbot20 no-mtop">
                     <input type="checkbox" name="show_primary_contact"<?php if(isset($client) && $client->show_primary_contact == 1){echo ' checked';}?> value="1" id="show_primary_contact">
                     <label for="show_primary_contact"><?php echo _l('show_primary_contact',_l('invoices').', '._l('estimates').', '._l('payments').', '._l('credit_notes')); ?></label>
                  </div>
               </div>
               <div class="col-md-6">
                  <?php $value=( isset($client) ? $client->company : ''); ?>
                  <?php $attrs = (isset($client) ? array() : array('autofocus'=>true)); ?>
                  <?php echo render_input( 'company', 'client_company',$value,'text',$attrs); ?>
                  <div id="company_exists_info" class="hide"></div>
                  <?php if(get_option('company_requires_vat_number_field') == 1){
                     $value=( isset($client) ? $client->vat : '');
                     echo render_input( 'vat', 'client_vat_number',$value);
                  } ?>
                  <?php $value=( isset($client) ? $client->phonenumber : ''); ?>
                  <?php echo render_input( 'phonenumber', 'client_phonenumber',$value); ?>
                  <?php if((isset($client) && empty($client->website)) || !isset($client)){
                     $value=( isset($client) ? $client->website : '');
                     echo render_input( 'website', 'client_website',$value);
                  } else { ?>
                     <div class="form-group">
                        <label for="website"><?php echo _l('client_website'); ?></label>
                        <div class="input-group">
                           <input type="text" name="website" id="website" value="<?php echo new_html_entity_decode($client->website); ?>" class="form-control">
                           <div class="input-group-addon">
                              <span><a href="<?php echo maybe_add_http($client->website); ?>" target="_blank" tabindex="-1"><i class="fa fa-globe"></i></a></span>
                           </div>
                        </div>
                     </div>
                  <?php }
                  ?>
                  <?php if(!isset($client)){ ?>
                     <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('customer_currency_change_notice'); ?>"></i>
                  <?php } ?>
                  <?php if(get_option('disable_language') == 0){ ?>
                  <div class="form-group">
                   <label for="default_language"><?php echo _l('localization_default_language'); ?></label>
                   <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                       class="form-control" name="default_language" id="default_language">
                       <option value=""><?php echo _l('system_default_string'); ?></option>
                        <?php foreach($this->app->get_available_languages() as $availableLanguage){
                           $selected = '';
                           if(isset($client)){
                              if($client->default_language == $availableLanguage){
                                 $selected = 'selected';
                              }
                           }
                           ?>
                           <option value="<?php echo new_html_entity_decode($availableLanguage); ?>" <?php echo new_html_entity_decode($selected); ?>><?php echo ucfirst($availableLanguage); ?></option>
                        <?php } ?>
                   </select>
               </div>

               <?php } ?>
            </div>
            <div class="col-md-6">
               <?php $value=( isset($client) ? $client->address : ''); ?>
               <?php echo render_textarea( 'address', 'client_address',$value); ?>
               <?php $value=( isset($client) ? $client->city : ''); ?>
               <?php echo render_input( 'city', 'client_city',$value); ?>
               <?php $value=( isset($client) ? $client->state : ''); ?>
               <?php echo render_input( 'state', 'client_state',$value); ?>
               <?php $value=( isset($client) ? $client->zip : ''); ?>
               <?php echo render_input( 'zip', 'client_postal_code',$value); ?>
               <?php $countries= get_all_countries();
               $customer_default_country = get_option('customer_default_country');
               $selected =( isset($client) ? $client->country : $customer_default_country);
               ?>
               <div class="form-group">
                   <label for="country"><?php echo _l('clients_country'); ?></label>
                   <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                       class="form-control" name="country" id="country">
                       <?php foreach($countries as $country){ ?>
                        <option value="" <?php if ($selected == $country['country_id']) {
                         echo 'selected';
                     } ?>><?php echo new_html_entity_decode($country['short_name']); ?></option>
                       <?php } ?>
                   </select>
               </div>
            </div>
         </div>
      </div>
      <?php if(isset($client)){ ?>
         <div role="tabpanel" class="tab-pane" id="customer_admins">
            <?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
               <a href="#" data-toggle="modal" data-target="#customer_admins_assign" class="btn btn-info mbot30"><?php echo _l('assign_admin'); ?></a>
            <?php } ?>
            <table class="table dt-table">
               <thead>
                  <tr>
                     <th><?php echo _l('staff_member'); ?></th>
                     <th><?php echo _l('customer_admin_date_assigned'); ?></th>
                     <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                        <th><?php echo _l('options'); ?></th>
                     <?php } ?>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($customer_admins as $c_admin){ ?>
                     <tr>
                        <td><a href="<?php echo admin_url('profile/'.$c_admin['staff_id']); ?>">
                           <?php echo staff_profile_image($c_admin['staff_id'], array(
                              'staff-profile-image-small',
                              'mright5'
                           ));
                           echo get_staff_full_name($c_admin['staff_id']); ?></a>
                        </td>
                        <td data-order="<?php echo new_html_entity_decode($c_admin['date_assigned']); ?>"><?php echo _dt($c_admin['date_assigned']); ?></td>
                        <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                           <td>
                              <a href="<?php echo admin_url('clients/delete_customer_admin/'.$client->userid.'/'.$c_admin['staff_id']); ?>" class="btn btn-danger _delete btn-icon"><i class="fa fa-remove"></i></a>
                           </td>
                        <?php } ?>
                     </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
      <?php } ?>
      <div role="tabpanel" class="tab-pane" id="billing_and_shipping">
         <div class="row">
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-6">
                     <h4 class="no-mtop"><?php echo _l('billing_address'); ?> <span class="pull-right pointer billing-same-as-customer" onclick="same_as_customer_info();"><small class="font-medium-xs"><?php echo _l('customer_billing_same_as_profile'); ?></small></span></h4>
                     <hr />
                     <?php $value=( isset($client) ? $client->billing_street : ''); ?>
                     <?php echo render_textarea( 'billing_street', 'billing_street',$value); ?>
                     <?php $value=( isset($client) ? $client->billing_city : ''); ?>
                     <?php echo render_input( 'billing_city', 'billing_city',$value); ?>
                     <?php $value=( isset($client) ? $client->billing_state : ''); ?>
                     <?php echo render_input( 'billing_state', 'billing_state',$value); ?>
                     <?php $value=( isset($client) ? $client->billing_zip : ''); ?>
                     <?php echo render_input( 'billing_zip', 'billing_zip',$value); ?>
                     <?php $selected=( isset($client) ? $client->billing_country : $customer_default_country ); ?>
                     <div class="form-group">
                      <label for="billing_country"><?php echo _l('billing_country'); ?></label>
                      <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                          class="form-control" name="billing_country" id="billing_country">
                          <?php foreach($countries as $country){ ?>
                           <option value="" <?php if ($selected == $country['country_id']) {
                            echo 'selected';
                        } ?>><?php echo new_html_entity_decode($country['short_name']); ?></option>
                          <?php } ?>
                      </select>
                  </div>
                  </div>
                  <div class="col-md-6">
                     <h4 class="no-mtop">
                        <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('customer_shipping_address_notice'); ?>"></i>
                        <?php echo _l('shipping_address'); ?><span class="pull-right pointer customer-copy-billing-address" onclick="customer_copy_billing_address();"><small class="font-medium-xs"><?php echo _l('customer_billing_copy'); ?></small></span>
                     </h4>
                     <hr />
                     <?php $value=( isset($client) ? $client->shipping_street : ''); ?>
                     <?php echo render_textarea( 'shipping_street', 'shipping_street',$value); ?>
                     <?php $value=( isset($client) ? $client->shipping_city : ''); ?>
                     <?php echo render_input( 'shipping_city', 'shipping_city',$value); ?>
                     <?php $value=( isset($client) ? $client->shipping_state : ''); ?>
                     <?php echo render_input( 'shipping_state', 'shipping_state',$value); ?>
                     <?php $value=( isset($client) ? $client->shipping_zip : ''); ?>
                     <?php echo render_input( 'shipping_zip', 'shipping_zip',$value); ?>
                     <?php $selected=( isset($client) ? $client->shipping_country : $customer_default_country ); ?>
                     <div class="form-group">
                      <label for="shipping_country"><?php echo _l('shipping_country'); ?></label>
                      <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                          class="form-control" name="shipping_country" id="shipping_country">
                          <?php foreach($countries as $country){ ?>
                           <option value="" <?php if ($selected == $country['country_id']) {
                            echo 'selected';
                        } ?>><?php echo new_html_entity_decode($country['short_name']); ?></option>
                          <?php } ?>
                      </select>
                  </div>
                  </div>
                  <?php if(isset($client) &&
                  (total_rows(db_prefix().'invoices',array('clientid'=>$client->userid)) > 0 || total_rows(db_prefix().'estimates',array('clientid'=>$client->userid)) > 0 || total_rows(db_prefix().'creditnotes',array('clientid'=>$client->userid)) > 0)){ ?>
                     <div class="col-md-12">
                        <div class="alert alert-warning">
                           <div class="checkbox checkbox-default">
                              <input type="checkbox" name="update_all_other_transactions" id="update_all_other_transactions">
                              <label for="update_all_other_transactions">
                                 <?php echo _l('customer_update_address_info_on_invoices'); ?><br />
                              </label>
                           </div>
                           <b><?php echo _l('customer_update_address_info_on_invoices_help'); ?></b>
                           <div class="checkbox checkbox-default">
                              <input type="checkbox" name="update_credit_notes" id="update_credit_notes">
                              <label for="update_credit_notes">
                                 <?php echo _l('customer_profile_update_credit_notes'); ?><br />
                              </label>
                           </div>
                        </div>
                     </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
         <button class="btn btn-info only-save customer-form-submiter">
            <?php echo _l( 'submit'); ?>
         </button>           
      </div>
   </div>
</div>

<?php echo form_close(); ?>
</div>

<?php $this->load->view('admin/clients/client_group'); ?>
