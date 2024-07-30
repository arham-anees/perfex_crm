<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<nav class="navbar navbar-default header">
   <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#theme-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <?php get_company_logo('','navbar-brand logo'); ?>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="theme-navbar-collapse">
         <ul class="nav navbar-nav navbar-right">
              
                <li class="customers-nav-item-Insurances-plan">
                       <a href="<?php echo site_url('affiliate/store/view_cart/'.$member_code); ?>">
                         <i class="fa fa-shopping-cart"></i>
                 <span class="text-white qty_total"></span>
                       </a>
                     </li>;
                <li class="customers-nav-item-Insurances-plan">
                   <a href="<?php echo site_url('affiliate/store/index/'.$member_code.'/1/0/0'); ?>">
                     <i class="fa fa-tags"></i>
                   </a>
                 </li>

            <?php if(is_client_logged_in()){ ?>
                 <li class="customers-nav-item-Insurances-plan">
                     <a href="<?php echo site_url('affiliate/store/order_list/'.$member_code); ?>"> <?php echo _l('order_list'); ?>
                     </a>
                 </li>
               <li class="dropdown customers-nav-item-profile">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                     <img src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>" data-toggle="tooltip" data-title="<?php echo html_escape($contact->firstname . ' ' .$contact->lastname); ?>" data-placement="bottom" class="client-profile-image-small mright5">
                     <span class="caret"></span>
                     </a>
                     <ul class="dropdown-menu animated fadeIn">
                        <li class="customers-nav-item-edit-profile">
                           <a href="<?php echo site_url('clients/profile'); ?>">
                              <?php echo _l('clients_nav_profile'); ?>
                           </a>
                        </li>
                     <li class="customers-nav-item-logout">
                        <a href="<?php echo site_url('authentication/logout'); ?>">
                           <?php echo _l('clients_nav_logout'); ?>
                        </a>
                     </li>
                  </ul>
               </li>
            <?php }else{ ?>
                <li class="customers-nav-item-register">
                  <a href="<?php echo site_url('authentication/register'); ?>"><?php echo _l('clients_nav_register'); ?></a>
               </li>
               <li class="customers-nav-item-login">
                  <a href="<?php echo site_url('authentication/login'); ?>"><?php echo _l('clients_nav_login'); ?></a>
               </li>
            <?php } ?> 
         </ul>
      </div>
      <!-- /.navbar-collapse -->
   </div>
   <!-- /.container-fluid -->
</nav>
