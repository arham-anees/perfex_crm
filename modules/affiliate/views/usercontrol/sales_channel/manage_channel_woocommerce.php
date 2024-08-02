
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
	 <div class="clearfix"></div><br>
	 <div class="col-md-12">
	 	<h4><i class="fa fa-list-ul">&nbsp;&nbsp;</i><?php echo new_html_entity_decode($title); ?></h4>
	 	<hr>
	 </div>
	     <div class="col-md-3"> 
		    <a href="#" id="add_channel_woocommerce" class="btn btn-info pull-left">
		        <?php echo _l('add'); ?>
		    </a>
		    <div class="clearfix"></div><br>
		 </div>
		 <div id="box-loadding"></div>
		<div class="clearfix"></div>
		<hr class="hr-panel-heading" />
		<div class="clearfix"></div>
		<table class="table dt-table">
             <thead>
                <th><?php echo _l('name_channel'); ?></th>
                <th><?php echo _l('url'); ?></th>
                <th><?php echo _l('consumer_key'); ?></th>
                <th><?php echo _l('consumer_secret'); ?></th>
                <th><?php echo _l('options'); ?></th>
             </thead>
            <tbody>
              <?php foreach($channels as $channel){ ?>
                <tr>
                  	<td><a href="<?php echo site_url('affiliate/usercontrol/woocommerce_channel_detail/'.$channel['id']); ?>"><?php echo new_html_entity_decode($channel['name_channel']); ?></a></td>
                  	<td><?php echo new_html_entity_decode($channel['url']); ?></td>
                  	<td><?php echo new_html_entity_decode($channel['consumer_key']); ?></td>
                  	<td><?php echo new_html_entity_decode($channel['consumer_secret']); ?></td>
                    <td><?php echo icon_btn(site_url('affiliate/usercontrol/woocommerce_channel_detail/'.$channel['id']), 'fa fa-eye', 'btn-default', [
                        'title' => _l('view')
                    ]).''.icon_btn('#', 'fa fa-edit', 'btn-default', [
                        'title' => _l('edit'),
                        'data-id' => new_html_entity_decode($channel['id']),
                        'data-name' => new_html_entity_decode($channel['name_channel']),
                        'data-key' => new_html_entity_decode($channel['consumer_key']),
                        'data-secret' => new_html_entity_decode($channel['consumer_secret']),
                        'data-url' => new_html_entity_decode($channel['url']),
                        'onclick' => 'edit(this); return false;'
                    ]).''.icon_btn(site_url('affiliate/usercontrol/delete_woocommerce_channel/'.$channel['id']), 'fa fa-remove', 'btn-danger _delete', [
                        'title' => _l('delete') 
                    ]); ?></td>
                </tr>
             <?php } ?>
            </tbody>
         </table> 
		
	</div>
  </div>
 </div>
<div class="modal fade" id="channel_woocommerce" tabindex="-1" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
                <span class="add-title"><?php echo _l('add_channel_woocommerce'); ?></span>
                <span class="update-title hide"><?php echo _l('edit_channel_woocommerce'); ?></span>
            </h4>
        </div>
    <?php echo form_open(site_url('affiliate/usercontrol/add_woocommerce_channel'),array('id'=>'form_add_channel_woocommerce')); ?>	            
        <div class="modal-body">
        	<?php echo form_hidden('id'); ?>
          <div class="form-group">
              <label class="control-label" for="name_channel"><small class="req text-danger">* </small><?php echo _l('name_channel'); ?></label>
              <input type="text" class="form-control" name="name_channel" id="name_channel">
          </div>
          <div class="form-group">
              <label class="control-label" for="url"><small class="req text-danger">* </small><?php echo _l('url'); ?></label>
              <input type="text" class="form-control" name="url" id="url">
          </div>
          <div class="form-group">
              <label class="control-label" for="consumer_key"><small class="req text-danger">* </small><?php echo _l('consumer_key'); ?></label>
              <input type="text" class="form-control" name="consumer_key" id="consumer_key">
          </div>
          <div class="form-group">
              <label class="control-label" for="consumer_secret"><small class="req text-danger">* </small><?php echo _l('consumer_secret'); ?></label>
              <input type="text" class="form-control" name="consumer_secret" id="consumer_secret">
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success test_connect hide"><?php echo _l('test_connect'); ?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info _delete"><?php echo _l('submit'); ?></button>
        </div>
    <?php echo form_close(); ?>	                
  	</div>
</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>
