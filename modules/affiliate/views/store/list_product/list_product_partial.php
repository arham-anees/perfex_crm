<?php 
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

	<div class="row">
    	<?php foreach ($product as $item) { ?>
			<div class="col-md-3 grid col-sm-6">
	            <div class="grid product-cell">
	                <div class="product-image"> 
	                	<a href="<?php 	echo site_url('affiliate/store/detailt/'.$member_code.'/'.$item['id']); ?>"> 
	                	<img class="pic-1" src="<?php echo new_html_entity_decode($this->Affiliate_store_model->get_image_items($item['id'])); ?>">
	                	 </a>               					                  
	                </div>
	                <div class="product-content">
	                    <div class="title"><a href="<?php echo site_url('affiliate/store/detailt/'.$member_code.'/'.$item['id']); ?>"><?php echo new_html_entity_decode($item['name']); ?></a></div> 
	                    <span class="price">
	                    		<?php echo app_format_money($item['price'],$currency_name); ?>	
	                    	                  	
	                    </span>					                    
	                </div>
	                <div class="pb-1 add-cart">
	                	<?php if($item['w_quantity'] != 0){  ?>
	                	<input type="number" name="qty" class="form-control qty" value="1" min="1" max="<?php echo new_html_entity_decode($item['w_quantity']); ?>" data-w_quantity="<?php echo new_html_entity_decode($item['w_quantity']); ?>">
	                	<button type="button" class="added btn btn-primary <?php if(in_array($item['id'],$array_list_id)){ echo ''; }else{ echo 'hide'; } ?>" data-id="<?php echo new_html_entity_decode($item['id']); ?>"><i class="fa fa-shopping-cart"></i> <?php echo _l('added'); ?></button>	
	                	<button type="button" class="add_cart btn btn-success <?php if(in_array($item['id'],$array_list_id)){ echo 'hide'; }else{ echo ''; } ?>" data-id="<?php echo new_html_entity_decode($item['id']); ?>"><i class="fa fa-shopping-cart"></i> <?php echo _l('add_to_cart'); ?></button>
	                	<?php }else{ ?>
	                		<button class="btn btn-default"><?php echo _l('out_of_stock'); ?></button>
	                	<?php } ?>

	                </div>
	            </div>
	        </div>
    	<?php } ?>	             
    </div>
    
