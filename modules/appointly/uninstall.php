<?php defined('BASEPATH') or exit('No direct script access allowed');
    
    $CI =& get_instance(); // Assuming you're in a CodeIgniter context

    // Remove custom route
    $route = "\n\$route['(:any)'] = 'appointly/appointments_public/create_external_appointment_booking_page/$1';\n";
    $routesPath = APPPATH . 'config/routes.php';

    // Read the current routes file content
    $routesContent = file_get_contents($routesPath);

    // Remove the custom route from the routes file
    $updatedRoutesContent = str_replace($route, '', $routesContent);

    // Write the updated content back to the routes file
    file_put_contents($routesPath, $updatedRoutesContent, LOCK_EX);

    
    // Execute the ALTER TABLE statement to drop the foreign key constraint
    $CI->db->query("
    ALTER TABLE " . db_prefix() . "appointly_appointments
    DROP FOREIGN KEY `fk_status_id`
    ");

    $CI->db->query("
        ALTER TABLE " . db_prefix() . "appointly_appointments
        DROP COLUMN status_id"
    );


    $CI->db->query("
    DROP TABLE " . db_prefix() . "appointly_appointments_subjects
    ");
    $CI->db->query("
    DROP TABLE " . db_prefix() . "appointly_appointments_statuses
    ");

    // Execute the ALTER TABLE statement to drop the foreign key constraint
    $CI->db->query("
    ALTER TABLE " . db_prefix() . "appointly_appointments
    DROP FOREIGN KEY `fk_booking_page_id`
    ");

    $CI->db->query("
    ALTER TABLE " . db_prefix() . "appointly_appointments
    DROP COLUMN booking_page_id
    ");

 
    $CI->db->query("
    DROP TABLE " . db_prefix() . "appointly_booking_pages
    ");


    // Target path in the Perfex CRM application
    $target_path = APPPATH . 'views/admin/leads/my_lead.php';

    // Remove the custom view file
    if (file_exists($target_path)) {
        @unlink($target_path);
    }