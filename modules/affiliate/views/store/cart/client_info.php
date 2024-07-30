<div id="wrapper" class="customer_profile">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <?php if(isset($client) && $client->registration_confirmed == 0 && is_admin()){ ?>
               <div class="alert alert-warning">
                  <?php echo _l('customer_requires_registration_confirmation'); ?>
                  <br />
                  <a href="<?php echo admin_url('clients/confirm_registration/'.$client->userid); ?>"><?php echo _l('confirm_registration'); ?></a>
               </div>
            <?php } else if(isset($client) && $client->active == 0 && $client->registration_confirmed == 1){ ?>
            <div class="alert alert-warning">
               <?php echo _l('customer_inactive_message'); ?>
               <br />
               <a href="<?php echo admin_url('clients/mark_as_active/'.$client->userid); ?>"><?php echo _l('mark_as_active'); ?></a>
            </div>
            <?php } ?>
            <?php if(isset($client) && (!has_permission('customers','','view') && is_customer_admin($client->userid))){?>
            <div class="alert alert-info">
               <?php echo _l('customer_admin_login_as_client_message',get_staff_full_name(get_staff_user_id())); ?>
            </div>
            <?php } ?>
         </div>
       

      
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <?php if(isset($client)){ ?>
                  <?php echo form_hidden('isedit'); ?>
                  <?php echo form_hidden('userid', $client->userid); ?>
                  <div class="clearfix"></div>
                  <?php } ?>
                  <div>
                     <div class="tab-content">
                           <?php $this->load->view((isset($tab) ? $tab['view'] : 'store/cart/profile')); ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php if($group == 'profile'){ ?>
         <div class="btn-bottom-pusher"></div>
      <?php } ?>
   </div>
</div>

<?php require 'modules/affiliate/assets/js/store/profile_js.php';?>
