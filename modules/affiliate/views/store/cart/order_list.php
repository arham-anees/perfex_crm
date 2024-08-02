<?php hooks()->do_action('app_affiliates_store_head'); ?>
<div class="col-md-12">
    <div class="panel_s">
        <div class="panel-body">
        	<div class="col-md-12"></div>
          <div class="horizontal-scrollable-tabs mb-5">
            <div class="horizontal-tabs mb-4">
              <ul class="nav nav-tabs nav-tabs-horizontal">
              	 <li<?php if($tab == 'processing'){echo " class='active'"; } ?>>
                    <a href="<?php echo site_url('affiliate/store/order_list/'.$member_code.'/processing'); ?>" >
                    	<?php echo _l('processing'); ?>
                    </a>
                </li>
                <li<?php if($tab == 'confirm'){echo " class='active'"; } ?>>
                    <a href="<?php echo site_url('affiliate/store/order_list/'.$member_code.'/confirm'); ?>" >
                    	<?php echo _l('confirm'); ?>
                    </a>
                </li>
                <li<?php if($tab == 'being_transported'){echo " class='active'"; } ?>>
                    <a href="<?php echo site_url('affiliate/store/order_list/'.$member_code.'/being_transported'); ?>" >
                    	<?php echo _l('being_transported'); ?>
                    </a>
                </li>
                <li<?php if($tab == 'finish'){echo " class='active'"; } ?>>
                    <a href="<?php echo site_url('affiliate/store/order_list/'.$member_code.'/finish'); ?>" >
                    	<?php echo _l('finish'); ?>
                    </a>
                </li>
                <li<?php if($tab == 'cancelled'){echo " class='active'"; } ?>>
                    <a href="<?php echo site_url('affiliate/store/order_list/'.$member_code.'/cancelled'); ?>" >
                    	<?php echo _l('cancelled'); ?>
                    </a>
                </li>                              	 
              </ul>
            </div>          
 				<?php 
        $this->load->view('store/cart/'.$tab); ?>
          </div>
        </div>
    </div>
</div>
