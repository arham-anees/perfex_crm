<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php include_once (APPPATH . 'views/admin/includes/helpers_bottom.php'); ?>

<?php hooks()->do_action('before_js_scripts_render');

?>

<?php echo app_compile_scripts('client');

/**
 * Global function for custom field of type hyperlink
 */
echo get_custom_fields_hyperlink_js_function();
?>
<?php
/**
 * Check for any alerts stored in session
 */
app_js_alerts();
?>