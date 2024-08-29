<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI =& get_instance(); // Assuming you're in a CodeIgniter context

// Remove custom route
$route = "\n\$route['a/(:any)'] = 'appointly/appointments_public/create_external_appointment_booking_page/$1';\n";
$routesPath = APPPATH . 'config/routes.php';

// Read the current routes file content
$routesContent = file_get_contents($routesPath);

// Remove the custom route from the routes file
$updatedRoutesContent = str_replace($route, '', $routesContent);

// Write the updated content back to the routes file
file_put_contents($routesPath, $updatedRoutesContent, LOCK_EX);



try {
    // Target path in the Perfex CRM application
    $target_path = APPPATH . 'views/admin/leads/my_lead.php';

    // Remove the custom view file
    if (file_exists($target_path)) {
        @unlink($target_path);
    }
} catch (Exception $e) {
}