<?php hooks()->do_action('app_affiliates_store_head'); ?>
<div id="wrapper" class="customer_profile">
   <div class="content">
      <div class="row">     

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
                     	<?php echo form_open($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
                           <?php $this->load->view('store/cart/profile_customer'); ?>
                        <?php echo form_close(); ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
