<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: LeadEvo API
Description: Used to receive prospects from leadev0
Version: 1.0.0
Author: Arham Anees
Author URI: 
Requires at least: 2.4.1
*/

$CI = &get_instance();

define('LEADEVO_API_MODULE_NAME', 'leadevo_api');

// hooks()->add_action('receive_prospect', 'on_receive_prospect');

/*
 * Register activation hook
 */
// register_activation_hook(LEADEVO_API_MODULE_NAME, 'leadevo_api_activation_hook');
// register_deactivation_hook(LEADEVO_API_MODULE_NAME, 'leadevo_api_deactivation_hook');

/**
 * The activation function.
 */
// function leadevo_api_activation_hook()
// {
//     require __DIR__ . '/install.php';
// }
// function leadevo_api_deactivation_hook()
// {
//     // require __DIR__ . '/deactivation.php';
// }

// function on_receive_prospect()
// {

// }