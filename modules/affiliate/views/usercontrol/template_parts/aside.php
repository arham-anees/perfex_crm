
<aside id="menu" class="sidebar">
   <ul class="nav metis-menu" id="side-menu">
      <li class="tw-mt-[63px] sm:tw-mt-0 -tw-mx-2 tw-overflow-hidden sm:tw-bg-neutral-900/50">
            <div id="logo" class="tw-py-2 tw-px-2 tw-h-[63px] tw-flex tw-items-center">
                <?php echo get_company_logo(get_admin_uri() . '/', '!tw-mt-0')?>
            </div>
        </li>
      
      <li class="menu-item-dashboard">
         <a href="<?php echo site_url('affiliate/usercontrol'); ?>" aria-expanded="false">
             <i class="fa fa-home menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('dashboard','', false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-affiliate-programs">
         <a href="<?php echo site_url('affiliate/usercontrol/sales_channel'); ?>" aria-expanded="false">
             <i class="fa fa-tasks menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('sales_channel','', false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-affiliate-programs">
         <a href="<?php echo site_url('affiliate/usercontrol/affiliate_programs'); ?>" aria-expanded="false">
             <i class="fa fa-wrench menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('affiliate_program','', false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-products-list">
         <a href="<?php echo site_url('affiliate/usercontrol/products_list'); ?>" aria-expanded="false">
             <i class="fa fa-cubes menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('products_list','', false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-my-orders">
         <a href="<?php echo site_url('affiliate/usercontrol/my_customers'); ?>" aria-expanded="false">
             <i class="fa fa-user menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('my_customers','', false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-my-orders">
         <a href="<?php echo site_url('affiliate/usercontrol/my_orders'); ?>" aria-expanded="false">
             <i class="fa fa-shopping-cart menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('my_orders','', false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-my-logs">
         <a href="<?php echo site_url('affiliate/usercontrol/my_logs'); ?>" aria-expanded="false">
             <i class="fa fa-list menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('my_logs','', false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-my-logs">
         <a href="<?php echo site_url('affiliate/usercontrol/transactions'); ?>" aria-expanded="false">
             <i class="fa fa-refresh menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('transactions','',false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-my-logs">
         <a href="<?php echo site_url('affiliate/usercontrol/withdraw_request'); ?>" aria-expanded="false">
             <i class="fa fa-download menu-icon"></i>
             <span class="menu-text">
              <?php echo _l('withdraw_request','',false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-my-reports">
         <a href="<?php echo site_url('affiliate/usercontrol/my_reports'); ?>" aria-expanded="false">
             <i class="fa fa-bar-chart menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('reports','', false); ?>
             </span>
         </a>
      </li>
      <li class="menu-item-my-reports">
         <a href="<?php echo site_url('affiliate/usercontrol/settings?group=automatic_sync_config'); ?>" aria-expanded="false">
             <i class="fa fa-cog menu-icon"></i>
             <span class="menu-text">
             <?php echo _l('settings','', false); ?>
             </span>
         </a>
      </li>
      <?php hooks()->do_action('after_render_single_aside_menu'); ?>
   </ul>
</aside>