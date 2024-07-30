<div class="col-md-12 head_title pt-2">
		<div><?php echo new_html_entity_decode($title_group); ?></div>
	</div>
<div class="product_list">			    		
<?php
 	  $this->load->view('store/list_product/list_product_partial');  ?>
 </div> 	  
<br>	
<br>	
<div class="clearfix"></div>
<div class="row text-right">
<?php
 for ($i=1; $i <= $total_page; $i++) {
 	$active = '';
 	if($page == $i){
 		$active = 'active';
 	}
   ?> 
 		<button class="btn btn_page <?php echo new_html_entity_decode($active); ?>" data-page="<?php echo new_html_entity_decode($i); ?>" data-member_code="<?php echo new_html_entity_decode($member_code); ?>" ><?php echo new_html_entity_decode($i); ?></button>
<?php } ?>	
</div>
<input type="hidden" name="group_id" value="<?php echo new_html_entity_decode($group_id); ?>">
