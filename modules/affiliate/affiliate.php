<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Affiliate Management
Description: This module allows online merchants to set up an affiliate marketing system for their online store
Version: 1.0.3
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

define('AFFILIATE_MODULE_NAME', 'affiliate');
define('AFFILIATE_MODULE_UPLOAD_FOLDER', module_dir_path(AFFILIATE_MODULE_NAME, 'uploads'));

/**
* Register activation module hook
*/
register_activation_hook(AFFILIATE_MODULE_NAME, 'affiliate_module_activation_hook');
hooks()->add_action('admin_init', 'affiliate_module_init_menu_items');
hooks()->add_action('app_admin_footer', 'affiliate_add_footer_components');
hooks()->add_action('app_admin_head', 'affiliate_add_head_components');
hooks()->add_filter('before_view_product_detail', 'apply_commission_view_product');
hooks()->add_action('app_affiliates_head', 'affiliate_portal_add_head_components');
hooks()->add_action('app_affiliates_footer', 'affiliate_portal_add_footer_components');
hooks()->add_action('app_affiliates_store_head', 'affiliate_store_portal_add_head_components');
hooks()->add_action('app_affiliates_store_footer', 'affiliate_store_portal_add_footer_components');
hooks()->add_filter('after_payment_added', 'apply_affiliate_programs');
hooks()->add_filter('credits_applied', 'credit_apply_affiliate_programs');
hooks()->add_action('before_cron_run', 'affiliate_scan_server_woo');
hooks()->add_action('after_custom_fields_select_options','init_affiliate_member_customfield');
hooks()->add_action('affiliate_init',AFFILIATE_MODULE_NAME.'_appint');
hooks()->add_action('pre_activate_module', AFFILIATE_MODULE_NAME.'_preactivate');
hooks()->add_action('pre_deactivate_module', AFFILIATE_MODULE_NAME.'_predeactivate');
define('VERSION_AFF', 1034);

function affiliate_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(AFFILIATE_MODULE_NAME, [AFFILIATE_MODULE_NAME]);
/**
* Load the module helper
*/
$CI = & get_instance();
$CI->load->helper(AFFILIATE_MODULE_NAME . '/affiliate');
/**
 * Init affiliate module menu items in setup in admin_init hook
 * @return null
 */
function affiliate_module_init_menu_items()
{
    $CI = &get_instance();
    if (affiliate_has_permission('dashboard', '', 'view') || affiliate_has_permission('member', '', 'view') || affiliate_has_permission('wallet', '', 'view') || affiliate_has_permission('affiliate_orders', '', 'view') || affiliate_has_permission('settings', '', 'view') || affiliate_has_permission('reports', '', 'view') || affiliate_has_permission('affiliate_program', '', 'view') || affiliate_has_permission('affiliate_logs', '', 'view') || is_admin()) {

        $CI->app_menu->add_sidebar_menu_item('affiliate', [
                'name'     => _l('affiliate'),
                'href'     => admin_url('affiliate'),
                'icon'     => 'fa fa-sitemap',    
                'position' => 30
        ]);
        
        if(affiliate_has_permission('dashboard', '', 'view')){
            $CI->app_menu->add_sidebar_children_item('affiliate', [
                'slug' => 'affiliate-dashboard',
                'name' => _l('als_dashboard'),
                'icon' => 'fa fa-home',
                'href' => admin_url('affiliate/dashboard'),
                'position' => 1,
            ]);
        }
        
        if(affiliate_has_permission('member', '', 'view')){
            $CI->app_menu->add_sidebar_children_item('affiliate', [
                'slug' => 'affiliate-members',
                'name' => _l('members'),
                'icon' => 'fa fa-users',
                'href' => admin_url('affiliate/members'),
                'position' => 2,
            ]);
        }

        if(affiliate_has_permission('affiliate_program', '', 'view')){
            $CI->app_menu->add_sidebar_children_item('affiliate', [
                'slug' => 'affiliate-programs',
                'name' => _l('affiliate_program'),
                'icon' => 'fa fa-square',
                'href' => admin_url('affiliate/affiliate_programs'),
                'position' => 3,
            ]);
        }
        
        if(affiliate_has_permission('affiliate_orders', '', 'view')){
            $CI->app_menu->add_sidebar_children_item('affiliate', [
                'slug' => 'affiliate-orders',
                'name' => _l('affiliate_orders'),
                'icon' => 'fa fa-list',
                'href' => admin_url('affiliate/affiliate_orders'),
                'position' => 4,
            ]);
        }
        
        if(affiliate_has_permission('affiliate_logs', '', 'view')){
            $CI->app_menu->add_sidebar_children_item('affiliate', [
                'slug' => 'affiliate-logs',
                'name' => _l('affiliate_logs'),
                'icon' => 'fa fa-list',
                'href' => admin_url('affiliate/affiliate_logs'),
                'position' => 5,
            ]);
        }
        
        if(affiliate_has_permission('wallet', '', 'view')){
            $CI->app_menu->add_sidebar_children_item('affiliate', [
                'slug' => 'affiliate-wallet',
                'name' => _l('wallet'),
                'icon' => 'fa fa-briefcase',
                'href' => admin_url('affiliate/wallet'),
                'position' => 6,
            ]);
        }
        
        if(affiliate_has_permission('reports', '', 'view')){
            $CI->app_menu->add_sidebar_children_item('affiliate', [
                'slug' => 'affiliate-reports',
                'name' => _l('als_reports'),
                'icon' => 'fa fa-bar-chart',
                'href' => admin_url('affiliate/reports'),
                'position' => 7,
            ]);
        }
        
        if(affiliate_has_permission('settings', '', 'view')){
            $CI->app_menu->add_sidebar_children_item('affiliate', [
                'slug' => 'affiliate-setting',
                'name' => _l('settings'),
                'icon' => 'fa fa-cog',
                'href' => admin_url('affiliate/settings?group=member_group'),
                'position' => 8,
            ]);
        }
    }
}

/**
 * affiliate add footer components
 * @return
 */
function affiliate_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, '/admin/affiliate/members') === false)) {
		echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/OrgChart-master/jquery.orgchart.js') . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/affiliate/affiliate_program') === false)) {
		echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}

    if (!(strpos($viewuri, '/admin/affiliate/affiliate_logs') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/affiliate_logs.js') . '?v='.VERSION_AFF. '"></script>';
    }

    if (!(strpos($viewuri, '/admin/affiliate/affiliate_orders') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/affiliate_orders.js'). '?v='.VERSION_AFF . '"></script>';
    }

    if (!(strpos($viewuri, '/admin/affiliate/order_detail') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/order_detail.js') . '?v='.VERSION_AFF. '"></script>';
    }

    if (!(strpos($viewuri, '/affiliate/wallet?group=withdraw_request') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/withdraw_request.js') . '?v='.VERSION_AFF. '"></script>';
    }

    if (!(strpos($viewuri, '/admin/affiliate/setting') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/settings.js') . '?v='.VERSION_AFF. '"></script>';
    }

    if (!(strpos($viewuri, '/admin/affiliate/wallet') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/wallet.js') . '?v='.VERSION_AFF. '"></script>';
    }

    if (!(strpos($viewuri, '/admin/affiliate/members?group=manage_admin') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/members/manage_admin.js') . '?v='.VERSION_AFF. '"></script>';
    }

    if (!(strpos($viewuri, '/admin/affiliate/members?group=registration_approval') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/members/registration_approval.js') . '?v='.VERSION_AFF. '"></script>';
    }

    
    if (!(strpos($viewuri, '/admin/affiliate/reports') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js'). '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
    }

    if (!(strpos($viewuri, '/admin/affiliate/dashboard') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js'). '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
    }
}

/**
 * affiliate add head components
 */
function affiliate_add_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];

	echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/css/style.css') . '?v='.VERSION_AFF. '"  rel="stylesheet" type="text/css" />';

	if (!(strpos($viewuri, '/admin/affiliate/members') === false)) {
		echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/OrgChart-master/jquery.orgchart.css') . '"  rel="stylesheet" type="text/css" />';
	}

    

	if (!(strpos($viewuri, '/admin/affiliate/affiliate_program') === false)) {
		echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
	}
}

/**
 * apply commission view product
 * 
 */
function apply_commission_view_product($data) {
    $CI = &get_instance();
    $affiliate_code = $CI->input->get('affiliate_code');
    $program = $CI->input->get('program');

    if($affiliate_code){
       commission_view_product(['product' => $data, 'affiliate_code' => $affiliate_code, 'program' => $program]);
    }
}

function affiliate_portal_add_head_components() {
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    if (!(strpos($viewuri, '/affiliate/usercontrol') === false)) {
        if(is_affiliate_logged_in()){
            echo '<link rel="stylesheet" href="'. site_url('assets/css/style.css'). '?v='.VERSION_AFF.'">';
        }
    }
    if (!(strpos($viewuri, '/affiliate/usercontrol/affiliate_program_detail') === false)) {
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.css') .'"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.css') . '"  rel="stylesheet" type="text/css" />';
    }

    if (!(strpos($viewuri, '/affiliate/usercontrol/woocommerce_channel_detail') === false)) {
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.css') .'"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.css') . '"  rel="stylesheet" type="text/css" />';
    }

    if (!(strpos($viewuri, '/affiliate/usercontrol/products_list') === false)) {
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.css') .'"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.css') . '"  rel="stylesheet" type="text/css" />';
    }

    if (!(strpos($viewuri, '/affiliate/usercontrol/sales_channel') === false) || !(strpos($viewuri,'/affiliate/usercontrol/woocommerce_channel_detail') === false)) {
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/css/woocommerce.css') . '?v='.VERSION_AFF.'" rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri, '/affiliate/usercontrol/settings?group=automatic_sync_config') === false)) {
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/css/woocommerce.css') . '?v='.VERSION_AFF.'" rel="stylesheet" type="text/css" />';
    }
}

function affiliate_portal_add_footer_components() {
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

    echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
    echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
    echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
    echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
    echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
    echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
    echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/portal/main.js') . '?v='.VERSION_AFF. '"></script>';
    
    if (!(strpos($viewuri, '/affiliate/usercontrol/my_customers') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/portal/my_customers.js') . '?v='.VERSION_AFF. '"></script>';
    }
    if (!(strpos($viewuri, '/affiliate/usercontrol/transactions') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/portal/transactions.js') . '?v='.VERSION_AFF. '"></script>';
    }
    if (!(strpos($viewuri, '/affiliate/usercontrol/products_list') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.jquery.min.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/portal/product.js') . '?v='.VERSION_AFF. '"></script>';
    }
    if (!(strpos($viewuri, '/affiliate/usercontrol/affiliate_program_detail') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.jquery.min.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/portal/affiliate_program_detail.js') . '?v='.VERSION_AFF. '"></script>';
    }
    if (!(strpos($viewuri, '/affiliate/usercontrol/woocommerce_channel_detail') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.jquery.min.js') . '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.js') . '"></script>';
    }
    if (!(strpos($viewuri, '/affiliate/usercontrol/order') === false)) {
        echo '<script src="' . site_url('assets\plugins\accounting.js\accounting.js') . '?v='.VERSION_AFF. '"></script>';
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/portal/order.js') . '?v='.VERSION_AFF. '"></script>';
    }

    if (!(strpos($viewuri, '/affiliate/usercontrol/sales_channel') === false) || !(strpos($viewuri,'/affiliate/usercontrol/woocommerce_channel_detail') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/portal/sales_channel/manage_channel_woocommerce.js') . '?v='.VERSION_AFF. '"></script>';
    }

    if (!(strpos($viewuri, '/affiliate/usercontrol/my_reports') === false)) {
        require 'modules/affiliate/assets/js/portal/reports_js.php';
    }

    if (!(strpos($viewuri, '/affiliate/usercontrol/settings?group=automatic_sync_config') === false)) {
        echo '<script src="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/portal/sales_channel/manage_channel_woocommerce.js') . '?v='.VERSION_AFF. '"></script>';
    }

    

    
}

function affiliate_store_portal_add_head_components() {
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

    if (!(strpos($viewuri,'/affiliate/store/index') === false)) {
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/css/store_sales.css') . '?v='.VERSION_AFF.'"  rel="stylesheet" type="text/css" />';
    }

    if (!(strpos($viewuri,'/affiliate/store/view_cart') === false) || !(strpos($viewuri,'affiliate/store/view_overview') === false) || !(strpos($viewuri,'/affiliate/store/view_order_detail') === false)) {
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/css/cart/invoice.css') . '?v='.VERSION_AFF.'"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri,'/affiliate/store/detailt') === false)) {
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/css/detailt_product/detailt_product.css') . '?v='.VERSION_AFF.'"  rel="stylesheet" type="text/css" />';
    } 
    if (!(strpos($viewuri,'/affiliate/store/order_list') === false)) {
        echo '<link href="' . module_dir_url(AFFILIATE_MODULE_NAME, 'assets/css/cart/order_list.css') . '?v='.VERSION_AFF.'"  rel="stylesheet" type="text/css" />';
    }
    
}

function affiliate_store_portal_add_footer_components() {
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

     if (!(strpos($viewuri,'/affiliate/store/index') === false) || !(strpos($viewuri,'/affiliate/store') === false)) {
        echo '<script src="'.module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/store/sales_client.js').'?v='.VERSION_AFF.'"></script>';
    }
    if (!(strpos($viewuri,'/affiliate/store/view_cart') === false)) {
        echo '<script src="'.module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/store/cart/invoice.js').'?v='.VERSION_AFF.'"></script>';
    }

    if (!(strpos($viewuri,'affiliate/store/view_overview') === false)) {
        echo '<script src="'.module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/trade_discount/view_overview.js').'?v='.VERSION_AFF.'"></script>';
    }

    if (!(strpos($viewuri,'affiliate/store/detailt') === false)) {
        echo '<script src="'.module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/detailt_product/detailt_product.js').'?v='.VERSION_AFF.'"></script>';
    }

    if (!(strpos($viewuri,'/affiliate/store/view_order_detail') === false)) {
        echo '<script src="'.module_dir_url(AFFILIATE_MODULE_NAME, 'assets/js/store/order_list/order_detailt_client.js').'?v='.VERSION_AFF.'"></script>';
    }
}

/**
 * apply affiliate program
 * @param integer $payment_id
 */
function apply_affiliate_programs($payment_id) {
    apply_affiliate_program($payment_id);
    return $payment_id;
}


/**
 * cron job sync data
 * @return 
 */

function affiliate_scan_server_woo(){  
    
    $CI = &get_instance();
    $stores = get_affiliate_all_store();
    $records_time1 = get_option('records_time1');
    $hour = time();
    foreach ($stores as $key => $store) {
        affiliate_cron_job_sync_woo($store['member_id'], $store['id']);
    }
    
    return true;
    
}


/**
 * credit apply affiliate program
 * @param integer $payment_id
 */
function credit_apply_affiliate_programs($credit) {
    credit_apply_affiliate_program($credit);
    return $credit;
}


/**
 * Initializes the affiliate customfield.
 *
 * @param      string  $custom_field  The custom field
 */
function init_affiliate_member_customfield($custom_field = ''){
    $select = '';
    if($custom_field != ''){
        if($custom_field->fieldto == 'aff_member'){
            $select = 'selected';
        }
    }

    $html = '<option value="aff_member" '.$select.' >'. _l('affiliate_member').'</option>';

    echo html_entity_decode($html);
}
function affiliate_appint(){
    $CI = & get_instance();    
    require_once 'libraries/gtsslib.php';
    $affiliate_api = new AffiliateLic();
    $affiliate_gtssres = $affiliate_api->verify_license(true);    
    if(!$affiliate_gtssres || ($affiliate_gtssres && isset($affiliate_gtssres['status']) && !$affiliate_gtssres['status'])){
         $CI->app_modules->deactivate(AFFILIATE_MODULE_NAME);
        set_alert('danger', "One of your modules failed its verification and got deactivated. Please reactivate or contact support.");
        redirect(admin_url('modules'));
    }    
}

function affiliate_preactivate($module_name){
    if ($module_name['system_name'] == AFFILIATE_MODULE_NAME) {             
        require_once 'libraries/gtsslib.php';
        $affiliate_api = new AffiliateLic();
        $affiliate_gtssres = $affiliate_api->verify_license();          
        if(!$affiliate_gtssres || ($affiliate_gtssres && isset($affiliate_gtssres['status']) && !$affiliate_gtssres['status'])){
             $CI = & get_instance();
            $data['submit_url'] = $module_name['system_name'].'/gtsverify/activate'; 
            $data['original_url'] = admin_url('modules/activate/'.AFFILIATE_MODULE_NAME); 
            $data['module_name'] = AFFILIATE_MODULE_NAME; 
            $data['title'] = "Module License Activation"; 
            echo $CI->load->view($module_name['system_name'].'/activate', $data, true);
            exit();
        }        
    }
}

function affiliate_predeactivate($module_name){
    if ($module_name['system_name'] == AFFILIATE_MODULE_NAME) {
        require_once 'libraries/gtsslib.php';
        $affiliate_api = new AffiliateLic();
        $affiliate_api->deactivate_license();
    }
}