<?php init_head();?>
<div id="wrapper" class="commission">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <?php $arrAtt = array();
                $arrAtt['data-type']='currency'; ?>
          <?php echo form_open($this->uri->uri_string(),array('id'=>'member-form','autocomplete'=>'off')); ?>
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <div class="row">
            <div class="col-md-6">
              <?php $value = (isset($member) ? $member->firstname : ''); ?>
              <?php $attrs = (isset($member) ? array() : array('autofocus'=>true)); ?>
              <?php echo render_input('firstname','staff_add_edit_firstname',$value,'text',$attrs); ?>
            </div>
            <div class="col-md-6">
              <?php $value = (isset($member) ? $member->lastname : ''); ?>
              <?php echo render_input('lastname','staff_add_edit_lastname',$value); ?>
            </div>
            <div class="col-md-6">
              <?php $value = (isset($member) ? $member->username : ''); ?>
              <?php echo render_input('username','username',$value, 'text',array('autocomplete'=>'off')); ?>
            </div>
            <div class="col-md-6">
              <?php $value = (isset($member) ? $member->email : ''); ?>
              <?php echo render_input('email','staff_add_edit_email',$value,'email',array('autocomplete'=>'off')); ?>
            </div>
            <div class="col-md-6">
              <?php $value = (isset($member) ? $member->phone : ''); ?>
              <?php echo render_input('phone','staff_add_edit_phonenumber',$value); ?>
            </div>
            <div class="col-md-6">
              <?php $vendor_status = [ 0 => ['id' => 'enable', 'name' => _l('enable')],
                                              1 => ['id' => 'disable', 'name' => _l('disable')]];
                $value = (isset($member) ? $member->vendor_status : 'disable');                      
              echo render_select('vendor_status', $vendor_status,array('id','name'),'vendor_status', $value); ?>
            </div>
            <div class="col-md-6">
              <?php 
                $value = (isset($member) ? $member->group : '');
              echo render_select('group', $groups,array('id','name'),'group', $value); ?>
            </div>
            <div class="col-md-6">
              <?php 
                $value = (isset($member) ? $member->under_affiliate : '');                      
              echo render_select('under_affiliate', $members,array('id','firstname', 'lastname'),'under_affiliate', $value); ?>
            </div>
            <div class="col-md-6">
              <?php $countries= get_all_countries();
                $value = (isset($member) ? $member->country : '');                      
              echo render_select('country', $countries,array('country_id',array( 'short_name')),'clients_country', $value); ?>
            </div>
            <div class="col-md-6">
               <label for="password" class="control-label"><?php echo _l('staff_add_edit_password'); ?></label>
               <div class="input-group">
                  <input type="password" class="form-control password" name="password" autocomplete="off">
                  <span class="input-group-addon">
                  <a href="#password" class="show_password" onclick="showPassword('password'); return false;"><i class="fa fa-eye"></i></a>
                  </span>
                  <span class="input-group-addon">
                  <a href="#" class="generate_password" onclick="generatePassword(this);return false;"><i class="fa fa-refresh"></i></a>
                  </span>
               </div>
               <?php if(isset($member)){ ?>
                     <p class="text-muted"><?php echo _l('staff_add_edit_password_note'); ?></p>
                   <?php } ?>
            </div>
          </div>
          <?php echo render_custom_fields('aff_member', $id); ?>
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
<?php require 'modules/affiliate/assets/js/members/member_js.php';?>
