<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
	<div class="content">
		<div class="row">
			
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
      
        <div class="panel-body mtop10">
        <div class="row">
        <div class="col-md-12">
        <p class="bold p_style"><?php echo _l('my_logs'); ?></p>
        <hr class="hr_style"/>
        <table class="table dt-table">
             <thead>
              <th><?php echo _l('program_name'); ?></th>
              <th><?php echo _l('user_ip'); ?></th>
              <th><?php echo _l('type'); ?></th>
              <th><?php echo _l('description'); ?></th>
              <th><?php echo _l('date'); ?></th>
             </thead>
            <tbody>
              <?php foreach($logs as $log){ ?>
                <tr>
                  <td><?php echo new_html_entity_decode($log['name']); ?></td>
                  <td><?php echo new_html_entity_decode($log['user_ip']); ?></td>
                  <td><?php echo _l($log['type']); ?></td>
                  <td><?php echo new_html_entity_decode($log['description']); ?></td>
                  <td><?php echo _dt($log['datecreated']); ?></td>
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
