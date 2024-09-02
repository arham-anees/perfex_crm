<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Translations
Description: Easy way to edit your translations for all languages and modules
Version: 1.0.4
Requires at least: 2.3.*
*/

define('TRANSLATIONS_MODULE_VERSION', '1.0.4');
define('TRANSLATIONS_MODULE_NAME', 'translations');
define('TRANSLATIONS_TABLE_NAME', db_prefix().'translations');

$CI = &get_instance();
/**
 * Load the module helper
 */
$CI->load->helper(TRANSLATIONS_MODULE_NAME . '/translations');

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(TRANSLATIONS_MODULE_NAME, [TRANSLATIONS_MODULE_NAME]);

// Adding setup menu item for module
hooks()->add_action('admin_init', 'add_setup_menu_translations_link');
// Adding permission for module
hooks()->add_action('staff_permissions', 'translations_staff_permissions', 10, 2);

/**
 * Register activation module hook
 */
register_activation_hook(TRANSLATIONS_MODULE_NAME, 'translations_activation_hook');

function translations_activation_hook(){
    require_once(__DIR__ . '/install.php');
}

/**
 * Register deactivation module hook
 */
register_deactivation_hook(TRANSLATIONS_MODULE_NAME, 'translations_de_activation_hook');

function translations_de_activation_hook(){
    require_once(__DIR__ . '/deactivate.php');
}

/**
 * Register uninstall module hook
 */
register_uninstall_hook(TRANSLATIONS_MODULE_NAME, 'translations_uninstall_hook');

function translations_uninstall_hook(){
    require_once(__DIR__ . '/uninstall.php');
}


// ADDING SCRIPT FILES
hooks()->add_action('before_compile_scripts_assets', 'add_translation_scripts');
