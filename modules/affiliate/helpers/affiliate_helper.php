<?php
defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('after_email_templates', 'add_purchase_email_templates');
/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations
 * @param  string $column column name to check
 * @param  string $table table name to check
 * @return boolean
 */

/**
 * Is affiliate logged in
 * @return boolean
 */
function is_affiliate_logged_in()
{
    return get_instance()->session->has_userdata('affiliate_logged_in');
}

/**
 * Return logged affiliate User ID from session
 * @return mixed
 */
function get_affiliate_user_id()
{
    if (!is_affiliate_logged_in()) {
        return false;
    }

    return get_instance()->session->userdata('affiliate_user_id');
}

/**
 * Return logged affiliate User Code from session
 * @return mixed
 */
function get_affiliate_user_code()
{
    if (!is_affiliate_logged_in()) {
        return false;
    }

    return get_instance()->session->userdata('affiliate_user_code');
}

/**
 * Gets the template part.
 *
 * @param      string   $name    The name
 * @param      array    $data    The data
 * @param      boolean  $return  The return
 *
 * @return     string   The template part.
 */
function get_affiliate_template_part($name, $data = [], $return = false)
{
    if ($name === '') {
        return '';
    }

    $CI   = &get_instance();
    $path = 'usercontrol/template_parts/';

    if ($return == true) {
        return $CI->load->view($path . $name, $data, true);
    }

    $CI->load->view($path . $name, $data);
}

/**
 * Gets the store template part.
 *
 * @param      string   $name    The name
 * @param      array    $data    The data
 * @param      boolean  $return  The return
 *
 * @return     string   The template part.
 */
function get_affiliate_store_template_part($name, $data = [], $return = false)
{
    if ($name === '') {
        return '';
    }

    $CI   = &get_instance();
    $path = 'store/themes/template_parts/';

    if ($return == true) {
        return $CI->load->view($path . $name, $data, true);
    }

    $CI->load->view($path . $name, $data);
}

/**
 * Get affiliate full name
 * @param  string $userid Optional
 * @return string Firstname and Lastname
 */
function get_affiliate_full_name($affiliateid = '')
{
    $tmpAffiliateUserId = get_affiliate_user_id();
    if ($affiliateid == '' || $affiliateid == $tmpAffiliateUserId) {
        if (isset($GLOBALS['affiliate'])) {
            return $GLOBALS['affiliate']->firstname . ' ' . $GLOBALS['affiliate']->lastname;
        }
        $affiliateid = $tmpAffiliateUserId;
    }

    $CI = &get_instance();

    $staff = $CI->app_object_cache->get('affiliate-full-name-data-' . $affiliateid);

    if (!$staff) {
        $CI->db->where('id', $affiliateid);
        $affiliate = $CI->db->select('firstname,lastname')->from(db_prefix() . 'affiliate_users')->get()->row();
        $CI->app_object_cache->add('affiliate-full-name-data-' . $affiliateid, $staff);
    }

    return html_escape($affiliate ? $affiliate->firstname . ' ' . $affiliate->lastname : '');
}

/**
 * commission view product
 * @param array
 */
function commission_view_product($data)
{
    $CI = &get_instance();
    $CI->load->model('affiliate/affiliate_model');
    return $CI->affiliate_model->commission_view_product($data);
}

/**
 * app affiliate footer
 */
function app_affiliates_footer()
{
    /**
     * Registered scripts
     */
    echo compile_theme_scripts();

    /**
     * @deprecated 2.3.0
     * Moved from themes/[THEME]/views/scripts.php
     * Use app_affiliates_footer hook instead
     */
    do_action_deprecated('affiliates_after_js_scripts_load', [], '2.3.0', 'app_affiliates_footer');

    hooks()->do_action('app_affiliates_footer');
}

/**
 * affiliates area head
 * @param  string $language @deprecated 2.3.0
 * @return null
 */
function app_affiliates_head($language = null)
{
    // $language param is deprecated
    if (is_null($language)) {
        $language = $GLOBALS['language'];
    }

    if (file_exists(FCPATH . 'assets/css/custom.css')) {
        echo '<link href="' . base_url('assets/css/custom.css') . '" rel="stylesheet" type="text/css" id="custom-css">' . PHP_EOL;
    }

    hooks()->do_action('app_affiliates_head');
}

/**
 * { app theme head hook }
 */
function app_theme_affiliate_head_hook()
{
    $CI = &get_instance();
    ob_start();
    echo get_custom_fields_hyperlink_js_function();

    if (get_option('use_recaptcha_customers_area') == 1
        && get_option('recaptcha_secret_key') != ''
        && get_option('recaptcha_site_key') != '') {
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
    }

    $isRTL = 'false';

    $locale = get_locale_key($GLOBALS['language']);

    $maxUploadSize = file_upload_max_size();

    $date_format = get_option('dateformat');
    $date_format = explode('|', $date_format);
    $date_format = $date_format[0];?>
    <script>
        <?php if (is_staff_logged_in()) {
        ?>
        var admin_url = '<?php echo admin_url(); ?>';
        <?php
}?>

        var site_url = '<?php echo site_url(''); ?>',
        app = {},
        cfh_popover_templates  = {};

        app.isRTL = '<?php echo html_entity_decode($isRTL); ?>';
        app.is_mobile = '<?php echo is_mobile(); ?>';
        app.months_json = '<?php echo json_encode([_l('January'), _l('February'), _l('March'), _l('April'), _l('May'), _l('June'), _l('July'), _l('August'), _l('September'), _l('October'), _l('November'), _l('December')]); ?>';

        app.browser = "<?php echo strtolower($CI->agent->browser()); ?>";
        app.max_php_ini_upload_size_bytes = "<?php echo html_entity_decode($maxUploadSize); ?>";
        app.locale = "<?php echo html_entity_decode($locale); ?>";

        app.options = {
            calendar_events_limit: "<?php echo get_option('calendar_events_limit'); ?>",
            calendar_first_day: "<?php echo get_option('calendar_first_day'); ?>",
            tables_pagination_limit: "<?php echo get_option('tables_pagination_limit'); ?>",
            enable_google_picker: "<?php echo get_option('enable_google_picker'); ?>",
            google_client_id: "<?php echo get_option('google_client_id'); ?>",
            google_api: "<?php echo get_option('google_api_key'); ?>",
            default_view_calendar: "<?php echo get_option('default_view_calendar'); ?>",
            timezone: "<?php echo get_option('default_timezone'); ?>",
            allowed_files: "<?php echo get_option('allowed_files'); ?>",
            date_format: "<?php echo html_entity_decode($date_format); ?>",
            time_format: "<?php echo get_option('time_format'); ?>",
        };

        app.lang = {
            file_exceeds_maxfile_size_in_form: "<?php echo _l('file_exceeds_maxfile_size_in_form'); ?>" + ' (<?php echo bytesToSize('', $maxUploadSize); ?>)',
            file_exceeds_max_filesize: "<?php echo _l('file_exceeds_max_filesize'); ?>" + ' (<?php echo bytesToSize('', $maxUploadSize); ?>)',
            validation_extension_not_allowed: "<?php echo _l('validation_extension_not_allowed'); ?>",
            sign_document_validation: "<?php echo _l('sign_document_validation'); ?>",
            dt_length_menu_all: "<?php echo _l('dt_length_menu_all'); ?>",
            drop_files_here_to_upload: "<?php echo _l('drop_files_here_to_upload'); ?>",
            browser_not_support_drag_and_drop: "<?php echo _l('browser_not_support_drag_and_drop'); ?>",
            confirm_action_prompt: "<?php echo _l('confirm_action_prompt'); ?>",
            datatables: <?php echo json_encode(get_datatables_language_array()); ?>,
            discussions_lang: <?php echo json_encode(get_project_discussions_language_array()); ?>,
        };
        window.addEventListener('load',function(){
            custom_fields_hyperlink();
        });
    </script>
    <?php

    _do_clients_area_deprecated_js_vars($date_format, $locale, $maxUploadSize, $isRTL);

    $contents = ob_get_contents();
    ob_end_clean();
    echo html_entity_decode($contents);
}

/**
 * Function that return order item taxes based on passed item id
 * @param  mixed $itemid
 * @return array
 */
function get_affiliate_order_item_taxes($itemid)
{
    $CI = &get_instance();
    $CI->db->where('order_item_id', $itemid);
    $taxes = $CI->db->get(db_prefix() . 'affiliate_order_item_taxs')->result_array();
    $i     = 0;
    foreach ($taxes as $tax) {
        $taxes[$i]['taxname'] = $tax['taxname'] . '|' . $tax['taxrate'];
        $i++;
    }

    return $taxes;
}

/**
 * apply affiliate program
 * @param integer $payment_id
 */
function apply_affiliate_program($payment_id)
{
    $CI = &get_instance();
    $CI->load->model('affiliate/affiliate_model');
    return $CI->affiliate_model->apply_affiliate_program($payment_id);
}

/**
 * get group name
 * @param  integer $id
 * @return array or row
 */
function get_affiliate_group_name($id = false)
{
    $CI = &get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        $gr = $CI->db->get(db_prefix() . 'items_groups')->row();
        if (isset($gr->name)) {
            return $gr->name;
        }
    }

    return '';
}

/**
 * get size name
 * @param  integer $id
 * @return array or row
 */
function get_affiliate_size_name($id = false)
{
    $CI = &get_instance();

    if (is_numeric($id)) {
        $CI->db->where('size_type_id', $id);

        return $CI->db->get(db_prefix() . 'ware_size_type')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from ' . db_prefix() . 'ware_size_type')->result_array();
    }

}

/**
 * get style name
 * @param  integer $id
 * @return array or row
 */
function get_affiliate_style_name($id = false)
{
    $CI = &get_instance();

    if (is_numeric($id)) {
        $CI->db->where('style_type_id', $id);
        return $CI->db->get(db_prefix() . 'ware_style_type')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from ' . db_prefix() . 'ware_style_type')->result_array();
    }

}

/**
 * get model name
 * @param  integer $id
 * @return array or row
 */
function get_affiliate_model_name($id = false)
{
    $CI = &get_instance();

    if (is_numeric($id)) {
        $CI->db->where('body_type_id', $id);

        return $CI->db->get(db_prefix() . 'ware_body_type')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from ' . db_prefix() . 'ware_body_type')->result_array();
    }

}

/**
 * get unit type
 * @param  integer $id
 * @return array or row
 */
function get_affiliate_unit_name($id = false)
{
    $CI = &get_instance();

    if (is_numeric($id)) {
        $CI->db->where('unit_type_id', $id);

        $unit = $CI->db->get(db_prefix() . 'ware_unit_type')->row();

        if (isset($unit->unit_name)) {
            return $unit->unit_name;
        }
    }

    return '';
}

/**
 * get product name
 * @param  integer $id
 * @return array or row
 */
function get_affiliate_product_name($id = false)
{
    $CI = &get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        $product = $CI->db->get(db_prefix() . 'items')->row();
        if ($product) {
            return $product->description;
        }
    }
    if ($id == false) {
        return $CI->db->query('select * from ' . db_prefix() . 'items')->result_array();
    }

}

/**
 * get product group name
 * @param  integer $id
 * @return array or row
 */
function get_affiliate_product_group_name($id = false)
{
    $CI = &get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        $group = $CI->db->get(db_prefix() . 'items_groups')->row();
        if ($group) {
            return $group->name;
        }
    }
    if ($id == false) {
        return $CI->db->query('select * from ' . db_prefix() . 'items_groups')->result_array();
    }

}

/**
 * @since 1.0.0
 * NOTE: This function will be deprecated in future updates, use staff_can($do, $feature = null, $staff_id = '') instead
 *
 * Check if staff user has permission
 * @param  string  $permission permission shortname
 * @param  mixed  $staffid if you want to check for particular staff
 * @return boolean
 */
function affiliate_has_permission($permission, $staffid = '', $can = '')
{
    return affiliate_staff_can($can, $permission, $staffid);
}

/**
 * @since  2.3.3
 * Helper function for checking staff capabilities, this function should be used instead of has_permission
 * Can be used e.q. staff_can('view', 'invoices');
 *
 * @param  string $capability         e.q. view | create | edit | delete | view_own | can_delete
 * @param  string $feature            the feature name e.q. invoices | estimates | contracts | my_module_name
 *
 *    NOTE: The $feature parameter is available as optional, but it's highly recommended always to be passed
 *    because of the uniqueness of the capability names.
 *    For example, if there is a capability "view" for feature "estimates" and also for "invoices" a capability "view" exists too
 *    In this case, if you don't pass the feature name, there may be inaccurate results
 *    If you are certain that your capability name is unique e.q. my_prefixed_capability_can_create , you don't need to pass the $feature
 *    and you can use this function as e.q. staff_can('my_prefixed_capability_can_create')
 *
 * @param  mixed $staff_id            staff id | if not passed, the logged in staff will be checked
 *
 * @return boolean
 */
function affiliate_staff_can($capability, $feature = null, $staff_id = '')
{
    $staff_id = $staff_id == '' ? get_staff_user_id() : $staff_id;

    /**
     * Maybe permission is function?
     * Example is_admin or is_staff_member
     */
    if (function_exists($capability) && is_callable($capability)) {
        return call_user_func($capability, $staff_id);
    }

    /**
     * If user is admin return true
     * Admins have all permissions
     */
    if (is_admin($staff_id)) {
        return true;
    }

    $CI = &get_instance();

    $permissions = null;
    /**
     * Stop making query if we are doing checking for current user
     * Current user is stored in $GLOBALS including the permissions
     */
    // if ((string) $staff_id === (string) get_staff_user_id() && isset($GLOBALS['current_user'])) {
    //     $permissions = $GLOBALS['current_user']->permissions;
    // }

    /**
     * Not current user?
     * Get permissions for this staff
     * Permissions will be cached in object cache upon first request
     */
    if (!$permissions) {
        if (!class_exists('affiliate_model', false)) {
            $CI->load->model('affiliate/affiliate_model');
        }

        $permissions = $CI->affiliate_model->get_admin_permissions($staff_id);
    }
    
    if (!$feature) {
        $retVal = in_array_multidimensional($permissions, 'capability', $capability);

        return hooks()->apply_filters('affiliate_staff_can', $retVal, $capability, $feature, $staff_id);
    }


    foreach ($permissions as $permission) {
        if ($feature == $permission['feature']
            && $capability == $permission['capability']) {
            return hooks()->apply_filters('affiliate_staff_can', true, $capability, $feature, $staff_id);
        }
    }

    return hooks()->apply_filters('affiliate_staff_can', false, $capability, $feature, $staff_id);
}

/**
 * sum transaction
 * @param  string $staffid
 * @param  string $date
 * @return integer
 */
function affiliate_sum_transaction($member_id = '', $date = '')
{
    if ($member_id == '') {
        $member_id = get_affiliate_user_id();
    }
    if ($date == true) {
        $count = sum_from_table(db_prefix() . 'affiliate_transactions', array('field' => 'amount', 'where' => array('member_id' => $member_id, 'year(datecreated)' => date('Y'), 'month(datecreated)' => date('m'))));
        if ($count) {
            return $count;
        }
        return 0;
    }

    $count = sum_from_table(db_prefix() . 'affiliate_transactions', array('field' => 'amount', 'where' => array('member_id' => $member_id)));
    if ($count) {
        return $count;
    }
    return 0;
}

/**
 * Maybe upload member profile image
 * @param  string $member_id member_id or current logged in member id will be used if not passed
 * @return boolean
 */
function handle_affiliate_member_profile_image_upload($member_id = '')
{
    if (isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['name'] != '') {

        if ($member_id == '') {
            $member_id = get_affiliate_user_id();
        }
        $path = AFFILIATE_MODULE_UPLOAD_FOLDER . '/member_image/' . $member_id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['profile_image']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

            $allowed_extensions = [
                'jpg',
                'jpeg',
                'png',
            ];

            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', _l('file_php_extension_blocked'));

                return false;
            }
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['profile_image']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI                       = &get_instance();
                $config                   = [];
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'thumb_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = hooks()->apply_filters('contact_profile_image_thumb_width', 320);
                $config['height']         = hooks()->apply_filters('contact_profile_image_thumb_height', 320);
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();
                $CI->image_lib->clear();
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'small_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = hooks()->apply_filters('contact_profile_image_small_width', 32);
                $config['height']         = hooks()->apply_filters('contact_profile_image_small_height', 32);
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();

                $CI->db->where('id', $member_id);
                $CI->db->update(db_prefix() . 'affiliate_users', [
                    'profile_image' => $filename,
                ]);
                // Remove original image
                unlink($newFilePath);

                return true;
            }
        }
    }

    return false;
}

/**
 * Return member profile image url
 * @param  mixed $member_id
 * @param  string $type
 * @return string
 */
function affiliate_member_profile_image_url($member_id, $type = 'small')
{
    $url  = base_url('assets/images/user-placeholder.jpg');
    $CI   = &get_instance();
    $path = $CI->app_object_cache->get('affiliate-member-profile-image-path-' . $member_id);

    if (!$path) {
        $CI->app_object_cache->add('affiliate-member-profile-image-path-' . $member_id, $url);

        $CI->db->select('profile_image');
        $CI->db->from(db_prefix() . 'affiliate_users');
        $CI->db->where('id', $member_id);
        $member = $CI->db->get()->row();

        if ($member && !empty($member->profile_image)) {
            $path = 'modules/affiliate/uploads/member_image/' . $member_id . '/' . $type . '_' . $member->profile_image;
            $CI->app_object_cache->set('affiliate-member-profile-image-path-' . $member_id, $path);
        }
    }

    if ($path && file_exists($path)) {
        $url = base_url($path);
    }
    return $url;
}

/**
 * get status modules for all
 * @param  string $module_name
 * @return boolean
 */
function affiliate_get_status_modules_all($module_name)
{
    $CI = &get_instance();

    $sql    = 'select * from ' . db_prefix() . 'modules where module_name = "' . $module_name . '" AND active =1 ';
    $module = $CI->db->query($sql)->row();
    if ($module) {
        return true;
    } else {
        return false;
    }
}

/**
 * { register theme affiliate assets hook }
 *
 * @param      <type>   $function  The function
 *
 * @return     boolean
 */
function register_theme_affiliate_assets_hook($function)
{
    if (hooks()->has_action('app_affiliate_assets', $function)) {
        return false;
    }

    return hooks()->add_action('app_affiliate_assets', $function, 1);
}

/**
 * init affiliate area assets.
 */
function init_affiliate_area_assets()
{
    // Used by themes to add assets
    hooks()->do_action('app_affiliate_assets');

    hooks()->do_action('app_affiliate_assets_added');
}

/**
 * get color type
 * @param  integer $id, string $index_name
 * @return array, object
 */
function get_affiliate_color_type($id = false)
{
    $CI = &get_instance();

    if (is_numeric($id)) {
        $CI->db->where('color_id', $id);

        return $CI->db->get(db_prefix() . 'ware_color')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblware_color')->result_array();
    }

}

/**
 * get unit type
 * @param  integer $id
 * @return array or row
 */
function get_affiliate_unit_type($id = false)
{
    $CI = &get_instance();

    if (is_numeric($id)) {
        $CI->db->where('unit_type_id', $id);

        return $CI->db->get(db_prefix() . 'ware_unit_type')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblware_unit_type')->result_array();
    }

}

/**
 * get all store
 * @return  stores
 */
function get_affiliate_all_store()
{
    $CI = &get_instance();
    $CI->load->model('affiliate/affiliate_model');
    return $CI->affiliate_model->get_woocommerce_channel();
}

/**
 * cron job sync woo
 * @param  string $type
 * @param  int $store
 * @param  int $minutes
 * @return  bolean
 */
function affiliate_cron_job_sync_woo($member_id, $store = '')
{

    $CI = &get_instance();

    $CI->load->model('affiliate/affiliate_model');
    $CI->load->model('affiliate/sync_woo_model');
    $CI->load->library('affiliate/asynclibrary');
    $hour      = time();
    $hour_cron = get_option('time_cron_woo');

    $config_store = $CI->affiliate_model->get_setting_auto_sync_store($member_id, $store);

    //records
    $records_time1 = $config_store->records_time1;
    $records_time2 = $config_store->records_time2;
    $records_time3 = $config_store->records_time3;
    $records_time4 = $config_store->records_time4;
    $records_time5 = $config_store->records_time5;
    $records_time6 = $config_store->records_time6;

    $sync_omni_sales_inventorys  = $config_store->sync_omni_sales_inventorys;
    $sync_omni_sales_products    = $config_store->sync_omni_sales_products;
    $sync_omni_sales_orders      = $config_store->sync_omni_sales_orders;
    $sync_omni_sales_description = $config_store->sync_omni_sales_description;
    $sync_omni_sales_images      = $config_store->sync_omni_sales_images;
    $price_crm_woo               = $config_store->price_crm_woo;

    $minute_sync_products_info_time1  = $config_store->time1;
    $minute_sync_inventory_info_time2 = $config_store->time2;
    $minute_sync_price_time3          = $config_store->time3;
    $minute_sync_decriptions_time4    = $config_store->time4;
    $minute_sync_images_time5         = $config_store->time5;
    $minute_sync_orders_time6         = $config_store->time6;

    if ($store != '') {
        if ($sync_omni_sales_products == "1") {
            if ($hour >= strtotime($records_time1)) {
                $result        = $CI->sync_woo_model->sync_from_the_system_to_the_store_single($member_id, $store);
                $records_time1 = strtotime($records_time1);
                $run_time2     = date("H:i:s", strtotime('+' . $minute_sync_products_info_time1 . ' minutes', $records_time1));
            }
        }

        if ($sync_omni_sales_inventorys == "1") {
            if ($hour >= strtotime($records_time2)) {

                $result        = $CI->sync_woo_model->process_inventory_synchronization_detail($member_id, $store);
                $records_time2 = strtotime($records_time2);
                $run_time2     = date("H:i:s", strtotime('+' . $minute_sync_inventory_info_time2 . ' minutes', $records_time2));
            }
        }

        if ($sync_omni_sales_description == "1") {
            if ($hour >= strtotime($records_time4)) {
                $CI->sync_woo_model->process_decriptions_synchronization_detail($member_id, $store);
                $records_time4 = strtotime($records_time4);
                $run_time4     = date("H:i:s", strtotime('+' . $minute_sync_decriptions_time4 . ' minutes', $records_time4));
            }
        }
        if ($sync_omni_sales_images == "1") {
            if ($hour >= strtotime($records_time5)) {
                $CI->sync_woo_model->process_images_synchronization_detail($member_id, $store);
                $records_time5 = strtotime($records_time5);
                $run_time5     = date("H:i:s", strtotime('+' . $minute_sync_images_time5 . ' minutes', $records_time5));
            }
        }
        if ($price_crm_woo == "1") {
            if ($hour >= strtotime($records_time3)) {
                $CI->sync_woo_model->process_price_synchronization($member_id, $store);
                $records_time3 = strtotime($records_time3);
                $run_time3     = date("H:i:s", strtotime('+' . $minute_sync_price_time3 . ' minutes', $records_time3));
            }
        }

    }

    if ($sync_omni_sales_orders == "1") {
        if ($hour >= strtotime($records_time6)) {
            $CI->sync_woo_model->process_orders_woo($member_id, $store);
            $records_time6 = strtotime($records_time6);
            $run_time6     = date("H:i:s", strtotime('+' . $minute_sync_orders_time6 . ' minutes', $records_time6));
        }
    }

    return true;
}

/**
 * apply affiliate program
 * @param integer $payment_id
 */
function credit_apply_affiliate_program($credit)
{
    $CI = &get_instance();
    $CI->load->model('affiliate/affiliate_model');
    return $CI->affiliate_model->credit_apply_affiliate_programs($credit);
}

/**
 * get status by index
 * @param  integer $index 
 * @return string        
 */
function af_get_status_by_index($index, $return_obj = false){
    $status = '';
    $slug = '';
    switch ($index) {
        case 0:
        $status = _l('omni_draft');
        $slug = 'draft';
        break;  
        case 1:
        $status = _l('processing');
        $slug = 'processing';
        break;      
        case 2:
        $status = _l('pending_payment');
        $slug = 'pending_payment';
        break;
        case 3:
        $status = _l('confirm');
        $slug = 'confirm';
        break;
        case 4:
        $status = _l('shipping');
        $slug = 'shipping';
        break;
        case 5:
        $status = _l('finish');
        $slug = 'finish';
        break;
        case 6:
        $status = _l('refund');
        $slug = 'refund';
        break;
        case 7:
        $status = _l('omni_return');
        $slug = 'return';
        break; 
        case 8:
        $status = _l('cancelled');
        $slug = 'cancelled';
        break;  
        case 9:
        $status = _l('omni_on_hold');
        $slug = 'on-hold';
        break;  
        case 10:
        $status = _l('omni_failed');
        $slug = 'failed';
        break; 
    }
    if($return_obj){
        $obj = new stdClass();
        $obj->status = $status;
        $obj->slug = $slug;
        return $obj;
    }
    return $status;
}

/**
 * get index by status
 * @param  string $status 
 * @return integer        
 */
function af_get_index_by_status($status){
    $index = 0;
    switch ($status) {
        case 'draft':
        $index = 0;
        break;  
        case 'processing':
        $index = 1;
        break;  
        case 'pending':
        $index = 2;
        break;     
        case 'pending_payment':
        $index = 2;
        break;
        case 'confirm':
        $index = 3;
        break;
        case 'shipping':
        $index = 4;
        break;
        case 'finish':
        $index = 5;
        break;
        case 'completed':
        $index = 5;
        break;
        case 'refund':
        $index = 6;
        break;
        case 'refunded':
        $index = 6;
        break;
        case 'return':
        $index = 7;
        break; 
        case 'cancelled':
        $index = 8;
        break; 
        case 'on-hold':
        $index = 9;
        break;
        case 'failed':
        $index = 10;
        break;
    }
    return $index;
}

/**
 * get status by index woo
 * @param  integer $index 
 * @return string        
 */
function af_get_status_by_index_woo($index){
    $status = '';
    switch ($index) {
        case 1:
        $status = 'processing';
        break;
        case 2:
        $status = 'pending';//pending_payment
        break;
        case 5:
        $status = "completed";//finish
        break;
        case 6:
        $status = 'refunded';//refund
        break;
        case 8:
        $status = 'cancelled';
        break;
        case 9:
        $status = 'on-hold';
        break;
        case 10:
        $status = 'failed';
        break;
    }
    return $status;
}

/**
     * [new_html_entity_decode description]
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
if (!function_exists('new_html_entity_decode')) {
    
    function new_html_entity_decode($str){
        return html_entity_decode($str ?? '');
    }
}