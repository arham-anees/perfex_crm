<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-4 col-md-offset-4 text-center mbot15">
    <h1 class="text-uppercase register-heading"><?php echo _l('clients_register_heading'); ?></h1>
</div>
<div class="col-md-10 col-md-offset-1 mbot40">
    <?php echo form_open('affiliate/authentication_affiliate/register', ['id'=>'register-form']); ?>
    <div class="panel_s">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group register-firstname-group">
                        <label class="control-label" for="firstname"><small class="req text-danger">* </small><?php echo _l('clients_firstname'); ?></label>
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo set_value('firstname'); ?>">
                        <?php echo form_error('firstname'); ?>
                    </div>
                    <div class="form-group register-lastname-group">
                        <label class="control-label" for="lastname"><small class="req text-danger">* </small><?php echo _l('clients_lastname'); ?></label>
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo set_value('lastname'); ?>">
                        <?php echo form_error('lastname'); ?>
                    </div>
                    <div class="form-group register-username-group">
                        <label class="control-label" for="username"><?php echo _l('username'); ?></label>
                        <input type="text" class="form-control" name="username" id="username" value="<?php echo set_value('username'); ?>">
                    </div>
                    <div class="form-group register-email-group">
                        <label class="control-label" for="email"><small class="req text-danger">* </small><?php echo _l('clients_email'); ?></label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>">
                        <?php echo form_error('email'); ?>
                    </div>
                    <div class="form-group register-contact-phone-group">
                        <label class="control-label" for="phonenumber"><small class="req text-danger">* </small><?php echo _l('clients_phone'); ?></label>
                        <input type="text" class="form-control" name="phonenumber" id="phonenumber" value="<?php echo set_value('phonenumber'); ?>">
                        <?php echo form_error('phonenumber'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group register-country-group">
                        <label class="control-label" for="lastname"><?php echo _l('clients_country'); ?></label>
                        <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" name="country" class="form-control" id="country">
                            <option value=""></option>
                            <?php foreach(get_all_countries() as $country){ ?>
                            <option value="<?php echo new_html_entity_decode($country['country_id']); ?>"<?php if(get_option('customer_default_country') == $country['country_id']){echo ' selected';} ?> <?php echo set_select('country', $country['country_id']); ?>><?php echo new_html_entity_decode($country['short_name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php $referral_code = '';
                            if($this->input->get('referral_code')){
                                $referral_code = $this->input->get('referral_code');
                            }else{
                                $referral_code = set_value('referral_code');
                            } ?>
                    <div class="form-group register-referral-code-group">
                        <label class="control-label" for="referral_code"><?php echo _l('referral_code'); ?></label>
                        <input type="text" class="form-control" name="referral_code" id="referral_code" defaultvalue="" value="<?php echo new_html_entity_decode($referral_code); ?>" autocomplete="off">
                    </div>
                    <div class="form-group register-password-group">
                        <label class="control-label" for="password"><small class="req text-danger">* </small><?php echo _l('clients_register_password'); ?></label>
                        <input type="password" class="form-control" name="password" id="password" autocomplete="off">
                        <?php echo form_error('password'); ?>
                    </div>
                    <div class="form-group register-password-repeat-group">
                        <label class="control-label" for="passwordr"><small class="req text-danger">* </small><?php echo _l('clients_register_password_repeat'); ?></label>
                        <input type="password" class="form-control" name="passwordr" id="passwordr" autocomplete="off">
                        <?php echo form_error('passwordr'); ?>
                    </div>
                </div>
       </div>
   </div>
</div>
<div class="row">
    <div class="col-md-12 text-center mbot40">
        <div class="form-group">
            <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info pull-right mleft5"><?php echo _l('clients_register_string'); ?></button>
            <a href="<?php echo site_url('affiliate/authentication_affiliate/login'); ?>" class="btn btn-default pull-right"><?php echo _l('back'); ?></a>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
</div>
