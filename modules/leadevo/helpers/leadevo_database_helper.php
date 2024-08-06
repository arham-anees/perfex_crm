<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('init_appointly_database_tables')) {
    /**
     * Init installation tables creation in database
     */
    function init_appointly_database_tables()
    {
        $CI = &get_instance();

        // $CI->db->query(
        //     "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_appointments (
        //         `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        //         `google_event_id` varchar(191) DEFAULT NULL,
        //         `google_calendar_link` varchar(191) DEFAULT NULL,
        //         `google_meet_link` varchar(191) DEFAULT NULL,
        //         `google_added_by_id` int(11) DEFAULT NULL,
        //         `outlook_event_id` VARCHAR(191) DEFAULT NULL,
        //         `outlook_calendar_link` VARCHAR(255) DEFAULT NULL,
        //         `outlook_added_by_id` INT(11) DEFAULT NULL,
        //         `subject` varchar(191) NOT NULL,
        //         `description` text,
        //         `email` varchar(191) DEFAULT NULL,
        //         `name` varchar(191) DEFAULT NULL,
        //         `phone` varchar(191) DEFAULT NULL,
        //         `address` varchar(191) DEFAULT NULL,
        //         `notes` longtext DEFAULT NULL,
        //         `contact_id` int(11) DEFAULT NULL,
        //         `by_sms` tinyint(1) DEFAULT NULL,
        //         `by_email` tinyint(1) DEFAULT NULL,
        //         `hash` varchar(191) DEFAULT NULL,
        //         `notification_date` datetime DEFAULT NULL,
        //         `external_notification_date` datetime DEFAULT NULL,
        //         `date` date NOT NULL,
        //         `start_hour` varchar(191) NOT NULL,
        //         `approved` tinyint(1) NOT NULL DEFAULT '0',
        //         `created_by` int(11) DEFAULT NULL,
        //         `reminder_before` int(11) DEFAULT NULL,
        //         `reminder_before_type` varchar(10) DEFAULT NULL,
        //         `finished` tinyint(1) NOT NULL DEFAULT '0',
        //         `cancelled` tinyint(1) NOT NULL DEFAULT '0',
        //         `cancel_notes` text,
        //         `source` varchar(191) DEFAULT NULL,
        //         `type_id` int(11) NOT NULL DEFAULT '0',
        //         `feedback` SMALLINT NULL DEFAULT NULL,
        //         `feedback_comment` TEXT NULL DEFAULT NULL,
        //         `recurring` int NOT NULL DEFAULT '0',
        //         `recurring_type` varchar(10) DEFAULT NULL,
        //         `repeat_every` INT NULL DEFAULT NULL,
        //         `custom_recurring` tinyint NOT NULL,
        //         `cycles` int NOT NULL DEFAULT '0',
        //         `total_cycles` int NOT NULL DEFAULT '0',
        //         `last_recurring_date` date DEFAULT NULL,           
        //         PRIMARY KEY (`id`)
        //         ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        // );
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "leadevo_prospect_statuses (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "leadevo_prospect_types (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "leadevo_prospect_categories (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "leadevo_campaign_statuses (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "leadevo_acquisition_channels (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "leadevo_industries (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "leadevo_industry_categories (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "leadevo_report_lead_statuses (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "leadevo_report_lead_reasons (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        
        
        add_option('delivery_settings_0stars', '10');
        add_option('delivery_settings_1stars', '20');
        add_option('delivery_settings_2stars', '40');
        add_option('delivery_settings_3stars', '10');
        add_option('delivery_settings_4stars', '10');
        add_option('delivery_settings_5stars', '10');
        add_option('delivery_settings', false);
        
        $CI->db->query(
            "INSERT INTO tblleads_sources (name)
            SELECT 'Invited by client'
            WHERE NOT EXISTS (
                SELECT 1 FROM tblleads_sources WHERE name = 'Invited by client'
            );"
        );
        
        
        
        
        
        // Add a new column with an optional relationship
        // $CI->db->query(
        //     "ALTER TABLE " . db_prefix() . "appointly_appointments 
        //     ADD COLUMN `status_id` int(11) UNSIGNED DEFAULT NULL;"
        // );

        // Optionally, add a foreign key constraint (uncomment if you need a foreign key)
        // $CI->db->query(
        //     "ALTER TABLE " . db_prefix() . "appointly_appointments 
        //     ADD CONSTRAINT `fk_status_id` FOREIGN KEY (`status_id`) 
        //     REFERENCES " . db_prefix() . "appointly_appointments_statuses(`id`) ON DELETE SET NULL ON UPDATE CASCADE;"
        // );
        
            // Create the subjects table
        // $CI->db->query(
        //     "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_appointments_subjects (
        //         `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        //         `subject` varchar(191) DEFAULT NULL,         
        //         PRIMARY KEY (`id`)
        //     ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        // );

        // $CI->db->query(
        //     "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_booking_pages (
        //         `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        //         `name` varchar(191) DEFAULT NULL,
        //         `description` varchar(191) DEFAULT NULL,
        //         `url` varchar(191) DEFAULT NULL,
        //         `duration_minutes` varchar(191) DEFAULT NULL,
        //         `simultaneous_appointments` int(11) UNSIGNED DEFAULT 1,
        //         `appointly_responsible_person` int(11) DEFAULT NULL,
        //         `callbacks_responsible_person` int(11) DEFAULT NULL,
        //         `appointly_available_hours` varchar(191) DEFAULT NULL,
        //         `appointly_default_feedbacks` varchar(191) DEFAULT NULL,
        //         `google_api_key` varchar(191) DEFAULT NULL,
        //         `google_client_id` varchar(191) DEFAULT NULL,
        //         `appointly_google_client_secret` varchar(191) DEFAULT NULL,
        //         `appointly_outlook_client_id` varchar(191) DEFAULT NULL,
        //         `appointly_appointments_recaptcha` bit DEFAULT NULL,
        //         `appointly_busy_times_enabled` bit DEFAULT NULL,
        //         `appointly_also_delete_in_google_calendar` bit DEFAULT NULL,
        //         `appointments_disable_weekends` bit DEFAULT NULL,
        //         `appointly_view_all_in_calendar` bit DEFAULT NULL,
        //         `appointly_client_meeting_approved_default` bit DEFAULT 0,
        //         `appointly_tab_on_clients_page` bit DEFAULT 0,
        //         `appointly_show_clients_schedule_button` bit DEFAULT 0,
        //         `appointments_show_past_times` bit DEFAULT 0,
        //         `callbacks_mode_enabled` bit DEFAULT 0,
        //         `is_active` bit DEFAULT b'1',           
        //         PRIMARY KEY (`id`)
        //     ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        // );
            //  // Add a new column with an optional relationship
            //  $CI->db->query(
            //     "ALTER TABLE " . db_prefix() . "appointly_appointments 
            //     ADD COLUMN `booking_page_id` int(11) UNSIGNED DEFAULT NULL;"
            // );
    
            // // Optionally, add a foreign key constraint (uncomment if you need a foreign key)
            // $CI->db->query(
            //     "ALTER TABLE " . db_prefix() . "appointly_appointments 
            //     ADD CONSTRAINT `fk_booking_page_id` FOREIGN KEY (`booking_page_id`) 
            //     REFERENCES " . db_prefix() . "appointly_booking_pages(`id`) ON DELETE SET NULL ON UPDATE CASCADE;"
            // );
        

        // $CI->db->query(
        //     "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_attendees (
        //         `staff_id` int(11) NOT NULL,
        //         `appointment_id` int(11) NOT NULL
        //         ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        // );
        // $CI->db->query(
        //     "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_appointment_types (
        //        `id` int(11) NOT NULL AUTO_INCREMENT,
        //        `type` varchar(191) NOT NULL,
        //        `color` varchar(191) NOT NULL,
        //        PRIMARY KEY (`id`)
        //        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        // );
        // $CI->db->query(
        //     "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_google (
        //        `id` int(11) NOT NULL AUTO_INCREMENT,
        //        `staff_id` int(11) NOT NULL,
        //        `access_token` varchar(191) NOT NULL,
        //        `refresh_token` varchar(191) NOT NULL,
        //        `expires_in` varchar(191) NOT NULL,
        //        PRIMARY KEY (`id`)
        //        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        // );

        // $CI->db->query(
        //     "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_callbacks (
        //        `id` int(11) NOT NULL AUTO_INCREMENT,
        //        `call_type` varchar(191) NOT NULL,
        //        `phone_number` varchar(191) NOT NULL,
        //        `timezone` varchar(191) NOT NULL,
        //        `firstname` varchar(191) NOT NULL,
        //        `lastname` varchar(191) NOT NULL,
        //        `status` varchar(191) NOT NULL DEFAULT '1',
        //        `message` text NOT NULL,
        //        `email`  varchar(191) NOT NULL ,
        //        `date_start` datetime NOT NULL,
        //        `date_end` datetime NOT NULL,
        //        `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        //        PRIMARY KEY (`id`)
        //        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        // );

        // $CI->db->query(
        //     "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_callbacks_assignees (
        //        `id` int(11) NOT NULL AUTO_INCREMENT,
        //        `callbackid` int(11) NOT NULL,
        //        `user_id` int(11) NOT NULL,
        //        PRIMARY KEY (`id`)
        //        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        // );

        // $CI->db->query(
        //     "INSERT INTO tblleads_sources (name)
        //     SELECT 'Booking Pages'
        //     WHERE NOT EXISTS (
        //         SELECT 1 FROM tblleads_sources WHERE name = 'Booking Pages'
        //     );"
        // );
        // $CI->db->query(
        //     "INSERT INTO tblleads_sources (name)
        //     SELECT 'Direct Appointment'
        //     WHERE NOT EXISTS (
        //         SELECT 1 FROM tblleads_sources WHERE name = 'Direct Appointment'
        //     );"
        // );

        checkForModuleReinstallation();
    }
}


if (!function_exists('init_leadevo_template_tables')) {
    /**
     * Insert email templates into database
     */
    function init_leadevo_template_tables()
    {
        create_email_template('You are invited!', '<span style=\"font-size: 12pt;\"> Hello {name}</span><br /><br /><span style=\"font-size: 12pt;\">I am using LeadEvo for trading leads. Please join us.<span style=\"font-size: 12pt;\"><br />Kind Regards</span><br /><br /><span style=\"font-size: 12pt;\">{email_signature}</span>', 'leadevo', 'Invite Friend', 'leadevo-friend-invitation');

    }
}


if (!function_exists('init_appointly_install_sequence')) {
    /**
     * Initialize tables content example data for email templates and sms in database
     */
    function init_appointly_install_sequence()
    {
        init_appointly_database_tables();
        init_leadevo_template_tables();
    }
}


if (!function_exists('checkForModuleReinstallation')) {
    /**
     * Percussion database checks
     */
    function checkForModuleReinstallation()
    {
        $CI = &get_instance();

    }
}
