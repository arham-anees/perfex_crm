<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_affiliates_store_head'); ?>
<div class="col-md-3 left_bar">
    <ul class="nav-tabs--vertical nav" role="navigation">
	    <li class="head text-center">
	    	<h5><?php echo _l('category'); ?></h5>
	    	<a href="<?php echo site_url('affiliate/store/index/'.$member_code.'/1/0/0'); ?>" class="view_all"><?php echo _l('all_products'); ?></a> 
	    </li>
	    <?php 
	    $data['title_group'] = $title_group;
	    	foreach ($group_product as $key => $value) {
	    		if($this->Affiliate_store_model->has_product_cat($member_code,$value['id'])){
	    			$active = '';
		    		if($value['id'] == $group_id){
		    		  $active = 'active';
		    		  $data['title_group'] = $value['name'];
		    		}    		

	    	 ?>

	    		<li class="nav-item <?php echo new_html_entity_decode($active); ?>">
					<a href="<?php echo site_url('affiliate/store/index/'.$member_code.'/1/'.$value['id'].'/0'); ?>" class="nav-link">
						<?php echo new_html_entity_decode($value['name']); ?>
					</a>
				</li>
		    <?php	
				  }
				}
		     ?>					
		
	</ul>
</div>
<div class="col-md-9 right_bar">

	<div class="row">
		<?php echo form_open(site_url('affiliate/store/search_product/'.$member_code.'/'.$group_id),array('id'=>'invoice-form','class'=>'_transaction_form invoice-form')); ?>
		<div class="col-md-11">
			<input type="text" name="keyword" class="form-control" placeholder="Search for products here ...">
			
		</div>
		<div class="col-md-1">
			<button type="submit" class="btn btn-info pull-right"><i class="fa fa-search"></i></button>
		</div>
		<?php echo form_close(); ?>

	</div>
	<?php $this->load->view('store/list_product/list_product_with_page',$data); ?>
<hr>
</div>
<div class="modal fade" id="alert_add" tabindex="-1" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
        	<div class="row">
	        	<div class="col-md-12 alert_content">
	        		<div class="clearfix"></div>
	        		<br>
	        		<br>
	        		<center class="add_success hide"><h4><?php echo _l('successfully_added'); ?></h4></center>
	        		<center class="add_error hide"><h4><?php echo _l('sorry_the_number_of_current_products_is_not_enough'); ?></h4></center>
	        		<br>
	        		<br>
					<div class="clearfix"></div>
	        	</div>
        	</div>
        </div>              
  	</div>
</div>
</div>

<?php hooks()->do_action('app_affiliates_store_footer'); ?>



