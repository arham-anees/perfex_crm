<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
	<div class="content">
		<div class="row">
			
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
      
        <div class="panel-body mtop10">
        <div class="row">
        <div class="col-md-12">
        <p class="bold p_style"><?php echo _l('affiliate_programs'); ?></p>
        <hr class="hr_style"/>
        <table class="table dt-table">
             <thead>
               <th><?php echo _l('name'); ?></th>
              <th><?php echo _l('from_date'); ?></th>
              <th><?php echo _l('to_date'); ?></th>
                <th><?php echo _l('commission'); ?></th>
                <th><?php echo _l('discount'); ?></th>
                <th><?php echo _l('priority'); ?></th>
                <th><?php echo _l('datecreated'); ?></th>
                <th><?php echo _l('options'); ?></th>
             </thead>
            <tbody>
               <?php foreach($affiliate_programs as $program){ ?>
                <tr>
                  <td><?php echo new_html_entity_decode($program['name']); ?></td>
                  <td><?php echo _d($program['from_date']); ?></td>
                  <td><?php echo _d($program['to_date']); ?></td>
                  <td><?php echo _l($program['enable_commission']); ?></td>
                  <td><?php echo _l($program['enable_discount']); ?></td>
                  <td><?php echo new_html_entity_decode($program['priority']); ?></td>
                  <td><?php echo _dt($program['datecreated']); ?></td>
                  <td><?php echo icon_btn(site_url('affiliate/usercontrol/affiliate_program_detail/'.$program['id']), 'fa fa-eye', 'btn-default', [
                        'title' => _l('view')
                    ]); ?></td>
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
