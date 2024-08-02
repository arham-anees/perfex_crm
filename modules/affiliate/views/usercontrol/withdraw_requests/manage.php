<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
	<div class="content">
		<div class="row">
			
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
      
        <div class="panel-body mtop10">
        <div class="row">
        <div class="col-md-12">
        <p class="bold p_style"><?php echo _l('withdraw_request'); ?></p>
        <hr class="hr_style"/>
        <table class="table dt-table">
             <thead>
                  <th><?php echo _l('id'); ?></th>
                  <th><?php echo _l('date'); ?></th>
                  <th><?php echo _l('payment_mode'); ?></th>
                  <th><?php echo _l('total'); ?></th>
                  <th><?php echo _l('status'); ?></th>
             </thead>
            <tbody>
              <?php foreach($withdraw_requests as $withdraw){ ?>
                <tr>
                  <td><?php echo new_html_entity_decode($withdraw['id']); ?></td>
                  <td><?php echo _d($withdraw['datecreated']); ?></td>
                  <td><?php echo new_html_entity_decode($withdraw['name']); ?></td>
                  <td><?php echo app_format_money($withdraw['total'], $currency->name); ?></td>
                  <?php 
                  if($withdraw['status'] == 1){
                      $status_name = _l('invoice_status_paid');
                      $label_class = 'success';
                  }elseif($withdraw['status'] == 2){
                      $status_name = _l('rejected');
                      $label_class = 'danger';
                  }else{
                      $status_name = _l('not_yet_approval');
                      $label_class = 'default';
                  }
                   ?>
                  <td><span class="label label-<?php echo new_html_entity_decode($label_class); ?> s-status commission-status-<?php echo new_html_entity_decode($withdraw['status']); ?>"><?php echo new_html_entity_decode($status_name); ?></span></td>
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
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>
