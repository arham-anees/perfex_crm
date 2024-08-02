<?php defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>
<li id="top_search" class="dropdown" data-toggle="tooltip" data-placement="bottom" data-title="<?php echo _l('search_by_tags'); ?>">
   <input type="search" id="search_input" class="form-control" placeholder="<?php echo _l('top_search_placeholder'); ?>">
   <div id="search_results">
   </div>
   <ul class="dropdown-menu search-results animated fadeIn no-mtop search-history" id="search-history">
   </ul>
</li>
<li id="top_search_button">
   <button class="btn"><i class="fa fa-search"></i></button>
</li>
<?php
$top_search_area = ob_get_contents();
ob_end_clean();
?>
<div id="header">
   <?php if(is_affiliate_logged_in()){ ?>
   <div class="hide-menu"><i class="fa fa-bars"></i></div>
<?php } ?>
   <nav>
   <?php if(is_affiliate_logged_in()){ ?>
   <ul class="nav navbar-nav navbar-right">
    <li class="icon header-user-profile" data-toggle="tooltip" title="<?php echo get_affiliate_full_name(); ?>" data-placement="bottom">
      <a href="#" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="false"><img src="<?php echo affiliate_member_profile_image_url($affiliate->id,'thumb'); ?>" data-toggle="tooltip" data-placement="bottom" class="client-profile-image-small mright5">
      </a>
      <ul class="dropdown-menu animated fadeIn">
         <li class="header-my-profile"><a href="<?php echo site_url('affiliate/usercontrol/profile'); ?>"><?php echo _l('nav_my_profile'); ?></a></li>
         <li class="header-logout">
            <a href="<?php echo site_url('affiliate/authentication_affiliate/logout'); ?>"><?php echo _l('nav_logout'); ?></a>
         </li>
      </ul>
   </li>
</ul>
<?php } ?>
</nav>
</div>