<?php 
$currency_name = '';
if(isset($base_currency)){
        $currency_name = $base_currency->name;
}

foreach ($cart_list as $key => $value) {
	$key = 0;
	$total = 0;
	$item_html = '';
     			    $data_detailt = $this->Affiliate_store_model->get_order_items($value['order_id']);
                    if($data_detailt){
                    	foreach ($data_detailt as $key => $item) {   
                    		$total += $item['qty'] * $item['rate'];
                    	}
                    }
	?>
	        <div class="row order">
                    <div class="panel_s">
                        <div class="panel-body">
                        	<div class="col-md-12 head-order">
                        		<div class="col-md-6">
								  <h5><?php echo _l('order_number');  ?>: <?php  echo new_html_entity_decode($value['order_code']); ?></h5>
								  <span><?php echo _l('order_date');  ?>: <?php  echo _dt($value['datecreated']); ?></span>
								</div>
								<div class="col-md-6">
									<h5><?php echo _l('receiver').': '.$value['company']; ?></h5>
								</div>
                        	</div>
                        	<div class="clearfix"></div>  
                        	<br>
                        	<br>
                        	<?php 
                    			if($data_detailt){
                    				foreach ($data_detailt as $key => $item) {   
			                         if($key == 0){
			                         	$key = 1;
                    				 ?>
                    				<div class="row"> 
                    				  <div class="col-md-12"> 
                    					<div class="col-md-8">                      				 
			                        		<a href="#">				                         
				                                <img class="product pic" src="<?php echo new_html_entity_decode($this->Affiliate_store_model->get_image_items($item['item_id'])); ?>">  
				                                <strong class="product_name">
				                                <?php echo new_html_entity_decode($item['description']); ?>			                                    
				                                </strong>
							                </a>
						                </div> 
						                <div class="col-md-4">
						                	<br>
						                	<br>
						                	<span class="total_order">
						                		<?php echo _l('total_orders').': '.app_format_money($total,'').' '.$currency_name; ?>
						                	</span>
						                </div>
                    				  </div>
                    				</div>
                        			<?php	}
                        			 }
                        		  }
                        	 ?>

                        	<div class="col-md-12">
                        		<a class="btn btn-danger pull-right" href="<?php echo site_url('affiliate/store/view_order_detail/'.$member_code.'/'.$value['order_id']); ?>"><i class="fa fa-eye"></i> <?php echo _l('view_orders'); ?></a>
                        	</div>                     		
                        </div>
                    </div>
            </div>
<?php }
?>