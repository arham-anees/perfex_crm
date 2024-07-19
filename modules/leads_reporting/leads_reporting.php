<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Leads Reporting
Description: It shows reports of leads with filter
Version: 1.3.0
Requires at least: 1.0.*
*/

hooks()->add_action('app_admin_head', 'leadsReport_head_components');
hooks()->add_action('app_admin_footer', 'leadsReport_footer_components');
// hooks()->add_action('after_email_templates', 'add_appointly_email_templates');
// hooks()->add_action('clients_init', 'appointly_clients_area_schedule_appointment');

define('LEADS_REPORT_MODULE_NAME', 'leads_reporting');

register_language_files(LEADS_REPORT_MODULE_NAME, ['leads']);
hooks()->add_action('admin_init', 'leadsReport_register_permissions');
hooks()->add_action('admin_init', 'leadsReport_register_menu_items');





/**
 * Hook for assigning staff permissions for appointments module.
 */
function leadsReport_register_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'     => _l('permission_view') . '(' . _l('permission_global') . ')',
        'view_own' => _l('permission_view_own'),
        'create'   => _l('permission_create'),
        'edit'     => _l('permission_edit'),
        'delete'   => _l('permission_delete'),
    ];

    register_staff_capabilities('appointments', $capabilities, _l('appointment_appointments'));
}


/**
 * Register new menu item in sidebar menu.
 */
function leadsReport_register_menu_items()
{
    $CI = &get_instance();

    if (staff_can('view', 'appointments') || staff_can('view_own', 'appointments')) {
        $CI->app_menu->add_sidebar_menu_item(LEADS_REPORT_MODULE_NAME, [
            'name'     => 'Leads Report',
            'href'     => admin_url('leads_reporting/leadsreport'),
            'position' => 20,
            'icon'     => 'fa-regular fa-chart-bar',
        ]);
    }
}

/**
 * Injects theme CSS.
 */
if ( ! function_exists('leadsReport_head_components')) {
    function leadsReport_head_components()
    {
        echo '<link href="'.module_dir_url(LEADS_REPORT_MODULE_NAME, 'assets/css/styles.css?v='.time()).'"  rel="stylesheet" type="text/css" >';
    }
}

/**
 * Injects theme JS for global modal.
 */
if ( ! function_exists('leadsReport_footer_components')) {
    function leadsReport_footer_components()
    {
        echo '<script src="'.module_dir_url(LEADS_REPORT_MODULE_NAME, 'assets/js/global.js?v='.time()).'"  type="text/javascript"></script>';
    }
}

/**
 * Fetches from database all staff assigned customers
 * If admin fetches all customers.
 *
 * @return array
 */
if ( ! function_exists('leadsReport_get_staff_customers')) {
    function leadsReport_get_staff_customers()
    {
        $CI = &get_instance();

        $staffCanViewAllClients = staff_can('view', 'customers');

        $CI->db->select('firstname, lastname, '.db_prefix().'contacts.id as contact_id, '.get_sql_select_client_company());
        $CI->db->where(db_prefix().'clients.active', '1');
        $CI->db->join(db_prefix().'clients', db_prefix().'clients.userid='.db_prefix().'contacts.userid', 'left');
        $CI->db->select(db_prefix().'clients.userid as client_id');

        if ( ! $staffCanViewAllClients) {
            $CI->db->where('('.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id='.get_staff_user_id().'))');
        }

        $result = $CI->db->get(db_prefix().'contacts')->result_array();

        foreach ($result as &$contact) {
            if ($contact['company']==$contact['firstname'].' '.$contact['lastname']) {
                $contact['company'] = _l('appointments_individual_contact');
            } else {
                $contact['company'] = ""._l('appointments_company_for_select')."(".$contact['company'].")";
            }
        }

        if ($CI->db->affected_rows()!==0) {
            return $result;
        } else {
            return [];
        }
    }
}