<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
	<div class="content">
		<div class="row">
			
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
      
        <div class="panel-body mtop10">
        <div class="row">
        <div class="col-md-12">
        <p class="bold p_style"><?php echo _l('my_customers'); ?></p>
        <hr class="hr_style"/>
        <a href="#" onclick="new_customer(); return false;" class="btn btn-info mbot10"><?php echo _l('new'); ?></a>
        <table class="table dt-table">
             <thead>
              <th><?php echo _l('company'); ?></th>
              <th><?php echo _l('contact_primary'); ?></th>
              <th><?php echo _l('company_primary_email'); ?></th>
              <th><?php echo _l('clients_list_phone'); ?></th>
              <th><?php echo _l('date_created'); ?></th>
             </thead>
            <tbody>
              <?php 
              foreach($customers as $customer){ ?>
                <tr>
                  <td><?php echo new_html_entity_decode($customer['company']); ?></td>
                  <td><?php echo new_html_entity_decode($customer['firstname'].' '.$customer['lastname']); ?></td>
                  <td><?php echo new_html_entity_decode($customer['contact_email']); ?></td>
                  <td><?php echo new_html_entity_decode($customer['contact_phonenumber']); ?></td>
                  <td><?php echo _dt($customer['datecreated']); ?></td>
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
<div class="modal fade" id="new_customer_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo _l('client'); ?></span>
                </h4>
            </div>
            <?php echo form_open('affiliate/usercontrol/add_customer',array('id'=>'new-customer-form')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                    <h4 class="bold register-contact-info-heading"><?php echo _l('client_register_contact_info'); ?></h4>
                    <div class="form-group mtop15 register-firstname-group">
                        <label class="control-label" for="firstname"><small class="req text-danger">* </small><?php echo _l('clients_firstname'); ?></label>
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo set_value('firstname'); ?>" required>
                    </div>
                    <div class="form-group register-lastname-group">
                        <label class="control-label" for="lastname"><small class="req text-danger">* </small><?php echo _l('clients_lastname'); ?></label>
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo set_value('lastname'); ?>" required>
                    </div>
                    <div class="form-group register-email-group">
                        <label class="control-label" for="email"><small class="req text-danger">* </small><?php echo _l('clients_email'); ?></label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" required>
                    </div>
                    <div class="form-group register-contact-phone-group">
                        <label class="control-label" for="contact_phonenumber"><?php echo _l('clients_phone'); ?></label>
                        <input type="text" class="form-control" name="contact_phonenumber" id="contact_phonenumber" value="<?php echo set_value('contact_phonenumber'); ?>">
                    </div>
                    <div class="form-group register-website-group">
                        <label class="control-label" for="website"><?php echo _l('client_website'); ?></label>
                        <input type="text" class="form-control" name="website" id="website" value="<?php echo set_value('website'); ?>">
                    </div>
                    <div class="form-group register-position-group">
                        <label class="control-label" for="title"><?php echo _l('contact_position'); ?></label>
                        <input type="text" class="form-control" name="title" id="title" value="<?php echo set_value('title'); ?>">
                    </div>
                    <div class="form-group register-password-group">
                        <label class="control-label" for="password"><small class="req text-danger">* </small><?php echo _l('clients_register_password'); ?></label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="form-group register-password-repeat-group">
                        <label class="control-label" for="passwordr"><small class="req text-danger">* </small><?php echo _l('clients_register_password_repeat'); ?></label>
                        <input type="password" class="form-control" name="passwordr" id="passwordr" required>
                        <div id="not_match_password">
                          
                        </div>
                    </div>
                    <div class="register-contact-custom-fields">
                        <?php echo render_custom_fields( 'contacts','',array('show_on_client_portal'=>1)); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="bold register-company-info-heading"><?php echo _l('client_register_company_info'); ?></h4>
                    <div class="form-group mtop15 register-company-group">
                        <label class="control-label" for="company"><small class="req text-danger">* </small><?php echo _l('clients_company'); ?></label>
                        <input type="text" class="form-control" name="company" id="company" value="<?php echo set_value('company'); ?>" required>
                    </div>
                    <?php if(get_option('company_requires_vat_number_field') == 1){ ?>
                    <div class="form-group register-vat-group">
                        <label class="control-label" for="vat"><?php echo _l('clients_vat'); ?></label>
                        <input type="text" class="form-control" name="vat" id="vat" value="<?php echo set_value('vat'); ?>">
                    </div>
                    <?php } ?>
                    <div class="form-group register-company-phone-group">
                        <label class="control-label" for="phonenumber"><?php echo _l('clients_phone'); ?></label>
                        <input type="text" class="form-control" name="phonenumber" id="phonenumber" value="<?php echo set_value('phonenumber'); ?>">
                    </div>
                    <div class="form-group register-country-group">
                        <label class="control-label" for="lastname"><?php echo _l('clients_country'); ?></label>
                        <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" name="country" class="form-control" id="country">
                            <option value=""></option>
                            <?php foreach(get_all_countries() as $country){ ?>
                            <option value="<?php echo new_html_entity_decode($country['country_id']); ?>"<?php if(get_option('customer_default_country') == $country['country_id']){echo ' selected';} ?> <?php echo set_select('country', $country['country_id']); ?>><?php echo new_html_entity_decode($country['short_name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group register-city-group">
                        <label class="control-label" for="city"><?php echo _l('clients_city'); ?></label>
                        <input type="text" class="form-control" name="city" id="city" value="<?php echo set_value('city'); ?>">
                    </div>
                    <div class="form-group register-address-group">
                        <label class="control-label" for="address"><?php echo _l('clients_address'); ?></label>
                        <input type="text" class="form-control" name="address" id="address" value="<?php echo set_value('address'); ?>">
                    </div>
                    <div class="form-group register-zip-group">
                        <label class="control-label" for="zip"><?php echo _l('clients_zip'); ?></label>
                        <input type="text" class="form-control" name="zip" id="zip" value="<?php echo set_value('zip'); ?>">
                    </div>
                    <div class="form-group register-state-group">
                        <label class="control-label" for="state"><?php echo _l('clients_state'); ?></label>
                        <input type="text" class="form-control" name="state" id="state" value="<?php echo set_value('state'); ?>">
                    </div>
                    <div class="register-company-custom-fields">
                        <?php echo render_custom_fields( 'customers','',array('show_on_client_portal'=>1)); ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <a href="#" onclick="new_customer_submit(); return false;" id="btn-submit" class="btn btn-info mbot10"><?php echo _l('save'); ?></a>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>
