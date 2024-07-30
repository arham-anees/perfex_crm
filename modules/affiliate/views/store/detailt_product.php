<?php hooks()->do_action('app_affiliates_store_head'); ?>
<?php $id = $detailt_product->id;
  $currency_name = '';
  if(isset($base_currency)){
    $currency_name = $base_currency->name;
  }
  $array_list_id = [];
       if(isset($_COOKIE['cart_id_list'])){
	       $list_id = $_COOKIE['cart_id_list'];
		    if($list_id){
		        $array_list_id = explode(',',$list_id);
		    }
       }
 ?>

<div class="wrapper row">
	<input type="hidden" name="id" value="<?php echo htmlentities($id); ?>">
	<div class="preview col-md-6">						
		<div class="preview-pic tab-content">
			<?php 
					$html_listimage = '';
					$list_filename = $this->Affiliate_store_model->get_all_image_file_name($id);
					if($list_filename){
					foreach ($list_filename as $key => $value) {
						$active = '';
						if($key == 0){
						  $active = 'active';
						}
					 ?>
		  			<div class="contain_image tab-pane <?php echo new_html_entity_decode($active); ?>" id="pic-<?php echo new_html_entity_decode($key); ?>">
		  				<img src="<?php echo site_url('modules/warehouse/uploads/item_img/'.$id.'/'.$value['file_name']); ?>" />
		  			</div>
					<?php
						$html_listimage.='<li class="'.html_entity_decode($active).'"><a data-target="#pic-'.html_entity_decode($key).'" data-toggle="tab"><img src="'.site_url('modules/warehouse/uploads/item_img/'.$id.'/'.$value['file_name']).'" /></a></li>';
					 }}else{ ?>
					 <div class="contain_image">
		  				<img src="<?php echo site_url('modules/affiliate/uploads/nul_image.jpg'); ?>" />
		  			</div>
					<?php } ?>		  
		</div>
		<ul class="preview-thumbnail nav nav-tabs">
			<?php echo new_html_entity_decode($html_listimage); ?>
		</ul>		
	</div>

	<div class="details col-md-6">
		<h3 class="product-title"><?php echo new_html_entity_decode($detailt_product->description); ?></h3>
		<span class="product-description"><a href="<?php echo site_url('affiliate/store/index/'.$member_code.'/1/'.$group_id); ?>"><?php echo _l('group').': '.$group; ?></a></span>
		
		<p class="product-description"><?php echo new_html_entity_decode($detailt_product->long_description); ?></p>
			<h4 class="price">
			 <?php echo _l('price').': '; ?> 
	            <span class="new-price"><?php echo app_format_money($detailt_product->rate, $currency_name); ?></span>	   
	      </h4>
		<br>
		<div class="col-md-12">
			
			<input type="hidden" name="quantity_available" value="<?php echo new_html_entity_decode($amount_in_stock); ?>">
		</div>
		<div class="action row">
			<div class="col-md-4">
				<div class="form-group">
				  <div class="input-group">
				    <span class="input-group-addon minus" onclick="change_qty(-1);">
				      <i class="fa fa-minus"></i>
				    </span>
				    <input id="quantity" class="form-control input-md text-center" type="number" value="1" min="1" max="<?php echo new_html_entity_decode($amount_in_stock); ?>">
				    <span class="input-group-addon plus" onclick="change_qty(1);">
				      <i class="fa fa-plus"></i>				      
				    </span>
				  </div>
				</div>
			</div>
			<div class="col-md-8">	
				<?php if($amount_in_stock > 0){ ?>
				<button class="btn btn-success input-lg add_to_cart <?php if(in_array($id, $array_list_id)){ echo 'hide'; }else{ echo ''; } ?>" type="button">
					<i class="fa fa-shopping-cart"></i> <?php echo _l('add_to_cart'); ?>
				</button>
				<button class="btn btn-primary input-lg added_to_cart <?php if(in_array($id, $array_list_id)){ echo ''; }else{ echo 'hide'; } ?>" type="button">
					<i class="fa fa-check"></i> <?php echo _l('added'); ?>
				</button>	
				<?php }else{ ?>			
				   <button class="btn btn-default input-lg" type="button"><?php echo _l('out_of_stock'); ?></button>	
				<?php } ?>			
			</div>
		</div>
	</div>
</div>
<hr>
<div class="col-md-12">	
	<div class="wrap_contents" >
		<?php
		  echo new_html_entity_decode($detailt_product->long_descriptions); 
		?>
	</div>
	<br>
</div>
  <div class="right-detail">
        <div class="line">&#9658;<?php echo _l('suggested_products'); ?></div>
           <div id="slidehind">    
           	<div class="frame-slide">
              <div class="frame" id="frameslide">
              	 <?php 
            	foreach ($product as $key => $item) { ?>
	            	<a href="<?php 	echo site_url('affiliate/store/detailt/'.$member_code.'/'.$item['id']); ?>">
	            		<?php 
		                	$file_name = $this->Affiliate_store_model->get_image_file_name($item['id']);
		               	?>
		               	<?php if($file_name && file_exists('modules/warehouse/uploads/item_img/'.$item['id'].'/'.$file_name->file_name)){ ?>
	                    <img src="<?php echo site_url('modules/warehouse/uploads/item_img/'.$item['id'].'/'.$file_name->file_name); ?>">
		               	<?php }else{ ?>
		  				<img src="<?php echo site_url('modules/affiliate/uploads/nul_image.jpg'); ?>" />
		               	<?php } ?>
	                    <div class="name"><?php echo new_html_entity_decode($item['name']); ?></div>
	                    <div class="price"><?php echo app_format_money($item['price'],$currency_name); ?></div>
	                </a>
            	<?php } ?>               
	         </div>
      	</div>
        <button class="btn btn-primary leftLst" onclick="scroll_slide(-1);"><i class="fa fa-chevron-left"></i></button>
        <button class="btn btn-primary rightLst" onclick="scroll_slide(1);"><i class="fa fa-chevron-right"></i></button>      	
	 </div>
   </div>
   <br>

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
