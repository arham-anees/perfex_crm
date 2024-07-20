<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Lead Evo
Description: A marketplace for managing and dealing in prospects
Version: 1.0.0
Requires at least: 1.0.*
*/

hooks()->add_action('app_admin_head', 'leadevo_head_components');
hooks()->add_action('app_admin_footer', 'leadevo_footer_components');
// hooks()->add_action('after_email_templates', 'add_appointly_email_templates');
// hooks()->add_action('clients_init', 'appointly_clients_area_schedule_appointment');

define('LEAD_EVO_MODULE_NAME', 'leadevo');

register_language_files(LEAD_EVO_MODULE_NAME, ['leadevo']);
hooks()->add_action('admin_init', 'leadevo_register_permissions');
hooks()->add_action('admin_init', 'leadevo_register_menu_items');





/**
 * Hook for assigning staff permissions for appointments module.
 */
function leadevo_register_permissions()
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
function leadevo_register_menu_items()
{
    $CI = &get_instance();

    if (staff_can('view', 'appointments') || staff_can('view_own', 'appointments')) {
        $CI->app_menu->add_sidebar_menu_item(LEAD_EVO_MODULE_NAME, [
            'name'     => 'Lead Evo',
            'href'     => admin_url('leadevo/leadsreport'),
            'position' => 21,
            'icon'     => 'fa-regular fa-chart-bar',
        ]);
    }
    $CI->app_menu->add_setup_menu_item(LEAD_EVO_MODULE_NAME, [
        'collapse' => true,
        'name'     => _l('leadevo_setup_menu'),
        'position' => 20,
        'badge'    => [],
    ]);
    $CI->app_menu->add_setup_children_item(LEAD_EVO_MODULE_NAME, [
        'slug'     => 'leadevo-setup-prospect-status',
        'name'     => _l('leadevo_setup_prospect_status_menu'),
        'href'     => admin_url('appointly/statuses'),
        'position' => 5,
        'badge'    => [],
    ]);
    $CI->app_menu->add_setup_children_item(LEAD_EVO_MODULE_NAME, [
        'slug'     => 'appointly-subjects',
        'name'     => _l('setup_appointments_subjects'),
        'href'     => admin_url('appointly/subjects'),
        'position' => 5,
        'badge'    => [],
    ]);
}

/**
 * Injects theme CSS.
 */
if ( ! function_exists('leadevo_head_components')) {
    function leadevo_head_components()
    {
        echo '<link href="'.module_dir_url(LEAD_EVO_MODULE_NAME, 'assets/css/styles.css?v='.time()).'"  rel="stylesheet" type="text/css" >';
    }
}

/**
 * Injects theme JS for global modal.
 */
if ( ! function_exists('leadevo_footer_components')) {
    function leadevo_footer_components()
    {
        echo '<script src="'.module_dir_url(LEAD_EVO_MODULE_NAME, 'assets/js/global.js?v='.time()).'"  type="text/javascript"></script>';
    }
}

/**
 * Fetches from database all staff assigned customers
 * If admin fetches all customers.
 *
 * @return array
 */
if ( ! function_exists('leadevo_get_staff_customers')) {
    function leadevo_get_staff_customers()
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