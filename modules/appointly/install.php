<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

$CI->load->helper('appointly' . '/appointly_database');

init_appointly_install_sequence();

$route = "\n\$route['a/(:any)'] = 'appointly/appointments_public/create_external_appointment_booking_page/$1';\n";
$routesPath = APPPATH . 'config/routes.php';

// Check if route already exists
$routesContent = file_get_contents($routesPath);
if (strpos($routesContent, $route) === false) {
    // Append the custom route to the routes file
    file_put_contents($routesPath, $route, FILE_APPEND | LOCK_EX);
}


// Path to the custom view file in the module
$source_path = module_dir_path('appointly') . 'views/admin/leads/my_lead.php';

// Target path in the Perfex CRM application
$target_path = APPPATH . 'views/admin/leads/my_lead.php';

// Copy the file
if (file_exists($source_path)) {
    @copy($source_path, $target_path);
}
