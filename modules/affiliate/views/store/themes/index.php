<?php defined('BASEPATH') or exit('No direct script access allowed');
echo theme_head_view();
    get_affiliate_store_template_part($navigationEnabled ? 'navigation' : '');
 ?>


<div id="wrapper" class="no-margin">
   <div id="content">
   <div class="container">
         <?php hooks()->do_action('affiliate_content_container_start'); ?>
         <?php echo theme_template_view(); ?>
   </div>
   </div>
   <?php
   echo theme_footer_view();
   ?>
</div>
<?php
  /* Always have app_affiliates_footer() just before the closing </body>  */
  app_affiliates_footer();
   /**
   * Check for any alerts stored in session
   */
   app_js_alerts();
   ?>
</body>
</html>