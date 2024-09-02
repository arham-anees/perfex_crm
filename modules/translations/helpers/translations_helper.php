<?php
defined('BASEPATH') or exit('No direct script access allowed');

function add_translation_scripts($group){
    if($group == "admin") {
        $CI = &get_instance();
        $CI->app_scripts->add('translations-js', module_dir_url(TRANSLATIONS_MODULE_NAME, 'assets/translations.js?v='.TRANSLATIONS_MODULE_VERSION));
    }
}

/**
 * Init language editor module menu items in setup in admin_init hook
 * @return null
 */
function add_setup_menu_translations_link(){
    if (has_permission('translations', '', 'view')) {
        $CI = &get_instance();
        /**
         * If the logged in user is administrator, add custom menu in Setup
         */
        $CI->app_menu->add_setup_menu_item('translations', [
            'href'     => admin_url('translations'),
            'name'     => _l('language_translations'),
            'position' => 300,
        ]);
    }
}

/**
 * Generate a beautiful name for module
 * @param $name string
 * @return string
 */
function beautify_module_name($name){
    return ucwords(str_replace("_", " ", $name));
}

/**
 * Staff permissions for translation module
 * @param $corePermissions array
 * @param $data array
 * @return array
 */
function translations_staff_permissions($corePermissions, $data){
    $corePermissions['translations'] = [
        'name'         => _l('language_translations'),
        'capabilities' => [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit' => _l('permission_edit'),
        ],
    ];
    return $corePermissions;
}