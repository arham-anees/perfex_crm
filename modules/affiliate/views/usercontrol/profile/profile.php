<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
	<div class="content">
		<div class="row section-heading section-profile">
	<div class="col-md-8">
		<?php echo form_open_multipart('affiliate/usercontrol/profile',array('autocomplete'=>'off')); ?>
		<?php echo form_hidden('profile',true); ?>
		<div class="panel_s">
			<div class="panel-body">
				<h4 class="no-margin section-text"><?php echo _l('clients_profile_heading'); ?></h4>
			</div>
		</div>
		<div class="panel_s">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<?php if($contact->profile_image == NULL){ ?>
								<div class="form-group profile-image-upload-group">
									<label for="profile_image" class="profile-image"><?php echo _l('client_profile_image'); ?></label>
									<input type="file" name="profile_image" class="form-control" id="profile_image">
								</div>
							<?php } ?>
							<?php if($contact->profile_image != NULL){
							 ?>
								<div class="form-group profile-image-group">
									<div class="row">
										<div class="col-md-9">
											<img src="<?php echo affiliate_member_profile_image_url($contact->id,'thumb'); ?>" class="client-profile-image-thumb">
										</div>
										<div class="col-md-3 text-right">
											<a href="<?php echo site_url('affiliate/usercontrol/remove_profile_image'); ?>"><i class="fa fa-remove text-danger"></i></a>
										</div>
									</div>
								</div>
							<?php } ?>

						</div>
						<div class="form-group profile-firstname-group">
							<label for="firstname"><?php echo _l('clients_firstname'); ?></label>
							<input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo set_value('firstname',$contact->firstname); ?>">
							<?php echo form_error('firstname'); ?>
						</div>
						<div class="form-group profile-lastname-group">
							<label for="lastname"><?php echo _l('clients_lastname'); ?></label>
							<input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo set_value('lastname',$contact->lastname); ?>">
							<?php echo form_error('lastname'); ?>
						</div>
						<div class="form-group register-username-group">
	                        <label class="control-label" for="username"><?php echo _l('username'); ?></label>
	                        <input type="text" class="form-control" name="username" id="username" value="<?php echo set_value('username',$contact->username); ?>">
	                    </div>
						<div class="form-group profile-email-group">
							<label for="email"><?php echo _l('clients_email'); ?></label>
							<input type="email" name="email" class="form-control" id="email" value="<?php echo new_html_entity_decode($contact->email); ?>">
							<?php echo form_error('email'); ?>
						</div>
						<div class="form-group profile-phone-group">
							<label for="phone"><?php echo _l('clients_phone'); ?></label>
							<input type="text" class="form-control" name="phone" id="phone" value="<?php echo new_html_entity_decode($contact->phone); ?>">
						</div>
						<div class="form-group register-country-group">
	                        <label class="control-label" for="lastname"><?php echo _l('clients_country'); ?></label>
	                        <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" name="country" class="form-control" id="country">
	                            <option value=""></option>
	                            <?php foreach(get_all_countries() as $country){ ?>
	                            <option value="<?php echo new_html_entity_decode($country['country_id']); ?>"<?php if($contact->country == $country['country_id']){echo ' selected';} ?> <?php echo set_select('country', $country['country_id']); ?>><?php echo new_html_entity_decode($country['short_name']); ?></option>
	                            <?php } ?>
	                        </select>
	                    </div>
						
					</div>
					<div class="row p15 contact-profile-save-section">
						<div class="col-md-12 text-right mtop20">
							<div class="form-group">
								<button type="submit" class="btn btn-info contact-profile-save"><?php echo _l('clients_edit_profile_update_btn'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
	<div class="col-md-4 contact-profile-change-password-section">
		<div class="panel_s section-heading section-change-password">
			<div class="panel-body">
				<h4 class="no-margin section-text"><?php echo _l('clients_edit_profile_change_password_heading'); ?></h4>
			</div>
		</div>
		<div class="panel_s">
			<div class="panel-body">
				<?php echo form_open('affiliate/usercontrol/profile'); ?>
				<?php echo form_hidden('change_password',true); ?>
				<div class="form-group">
					<label for="oldpassword"><?php echo _l('clients_edit_profile_old_password'); ?></label>
					<input type="password" class="form-control" name="oldpassword" id="oldpassword">
					<?php echo form_error('oldpassword'); ?>
				</div>
				<div class="form-group">
					<label for="newpassword"><?php echo _l('clients_edit_profile_new_password'); ?></label>
					<input type="password" class="form-control" name="newpassword" id="newpassword">
					<?php echo form_error('newpassword'); ?>
				</div>
				<div class="form-group">
					<label for="newpasswordr"><?php echo _l('clients_edit_profile_new_password_repeat'); ?></label>
					<input type="password" class="form-control" name="newpasswordr" id="newpasswordr">
					<?php echo form_error('newpasswordr'); ?>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-info btn-block"><?php echo _l('clients_edit_profile_change_password_btn'); ?></button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

</div>
	</div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>

