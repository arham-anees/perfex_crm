<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_affiliates_store_head'); ?>
<div id="wrapper">
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
    	<div class="col-md-12 infor_page">
    		<strong><?php echo new_html_entity_decode($content); ?></strong>	   		    		
    	</div>
    	<br>
    	<br>
    	<br>
    	<br>
    	<div class="col-md-12 text-center">
    		<a href="<?php if($previous_link!=''){ echo new_html_entity_decode($previous_link); }else{ echo 'javascript:history.back()'; } ?> " class="btn btn-primary">
    			<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                <?php 
                    if(isset($link_text)){
                        echo new_html_entity_decode($link_text);
                    }
                    else{
                        echo _l('return_to_the_previous_page');
                    }
                 ?>
             </a>
             &nbsp;
             <?php 
                if(isset($custom_link)){ ?>
                     <a href="<?php echo new_html_entity_decode($custom_link) ?>" class="btn btn-danger">
                        <?php 
                            if(isset($custom_link_text)){
                                echo new_html_entity_decode($custom_link_text);
                            }                    
                         ?>
                     </a>
              <?php   }
              ?>            
    	</div>
	  </div>
  </div>
 </div>
</div>


