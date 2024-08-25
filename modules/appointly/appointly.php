<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Appointly
Description: Perfex CRM Appointments & Callback module
Version: 1.2.4
Author: iDev
Author URI: https://idevalex.com
Requires at least: 2.4.1
*/

$CI = &get_instance();

define('APPOINTLY_MODULE_NAME', 'appointly');
define('APPOINTLY_SMS_APPOINTMENT_APPROVED_TO_CLIENT', 'appointly_appointment_approved_send_to_client');
define('APPOINTLY_SMS_APPOINTMENT_CANCELLED_TO_CLIENT', 'appointly_appointment_cancelled_to_client');
define('APPOINTLY_SMS_APPOINTMENT_APPOINTMENT_REMINDER_TO_CLIENT', 'appointly_appointment_reminder_to_client');

hooks()->add_action('admin_init', 'appointly_register_permissions');
hooks()->add_action('admin_init', 'appointly_register_menu_items');
hooks()->add_action('after_cron_run', 'appointly_send_email_templates');
// hooks()->add_action('after_cron_run', 'appointly_send_email_templates_auto');
hooks()->add_action('after_cron_run', 'appointly_recurring_events');
register_cron_task('appointly_send_email_templates_auto');

register_merge_fields('appointly/merge_fields/appointly_merge_fields');

hooks()->add_filter('other_merge_fields_available_for', 'appointly_register_other_merge_fields');
hooks()->add_filter('available_merge_fields', 'appointly_allow_staff_merge_fields_for_appointment_templates');
hooks()->add_filter('get_dashboard_widgets', 'appointly_register_dashboard_widgets');
hooks()->add_filter('calendar_data', 'appointly_register_appointments_on_calendar', 10, 2);


/**
 * Register appointments on staff and clients calendar.
 *
 * @param $data
 * @param $config
 *
 * @return mixed
 */
function appointly_register_appointments_on_calendar($data, $config)
{
    $CI = &get_instance();
    $CI->load->model('appointly/appointly_model', 'apm');

    return $CI->apm->getCalendarData($config['start'], $config['end'], $data);
}


hooks()->add_action('after_custom_fields_select_options', 'appointly_custom_fields');
hooks()->add_action('after_custom_fields_select_options', 'appointly_booking_fields');
/**
 * Register new custom fields for
 *
 * @param $custom_field
 */
function appointly_custom_fields($custom_field)
{
    $selected = (isset($custom_field) && $custom_field->fieldto == 'appointly') ? 'selected' : '';
    echo '<option value="appointly"  ' . ($selected) . '>' . _l('appointment_appointments') . '</option>';
}
function appointly_booking_fields($custom_field)
{
    $selected = (isset($custom_field) && $custom_field->fieldto == 'bookings') ? 'selected' : '';
    echo '<option value="bookings"  ' . ($selected) . '>' . _l('bookings') . '</option>';
}

/**
 * Get today's appointments to render in dashboard widget.
 *
 * @param array $widgets
 *
 * @return array
 */
function appointly_register_dashboard_widgets($widgets)
{
    $widgets[] = [
        'container' => 'left-8',
        'path' => 'appointly/widgets/today_appointments',
    ];

    return $widgets;
}

/**
 * Get staff fields and insert into email templates for appointly.
 *
 * @param [array] $fields
 *
 * @return array
 */
function appointly_allow_staff_merge_fields_for_appointment_templates($fields)
{
    $appointlyStaffFields = ['{staff_firstname}', '{staff_lastname}'];

    foreach ($fields as $index => $group) {
        foreach ($group as $key => $groupFields) {
            if ($key == 'staff') {
                foreach ($groupFields as $groupIndex => $groupField) {
                    if (in_array($groupField['key'], $appointlyStaffFields)) {
                        $fields[$index][$key][$groupIndex]['available'] = array_merge($fields[$index][$key][$groupIndex]['available'], ['appointly']);
                    }
                }
                break;
            }
        }
    }

    return $fields;
}

/**
 * Register other merge fields for appointly.
 *
 * @param array $for
 *
 * @return array
 */
function appointly_register_other_merge_fields($for)
{
    $for[] = 'appointly';

    return $for;
}

/**
 * Hook for assigning staff permissions for appointments module.
 */
function appointly_register_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
        'view_own' => _l('permission_view_own'),
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('appointments', $capabilities, _l('appointment_appointments'));
}

/**
 * Register new menu item in sidebar menu.
 */
function appointly_register_menu_items()
{
    $CI = &get_instance();

    if (staff_can('view', 'appointments') || staff_can('view_own', 'appointments')) {
        $CI->app_menu->add_sidebar_menu_item(APPOINTLY_MODULE_NAME, [
            'name' => 'appointly_module_name',
            'href' => admin_url('appointly/appointments'),
            'position' => 10,
            'icon' => 'fa-regular fa-calendar',
        ]);

        $CI->app_menu->add_sidebar_children_item(APPOINTLY_MODULE_NAME, [
            'slug' => 'appointly-user-dashboard',
            'name' => 'appointment_appointments',
            'href' => admin_url('appointly/appointments'),
            'position' => 5,
            'icon' => 'fa fa-th-list',
        ]);

        $CI->app_menu->add_sidebar_children_item(APPOINTLY_MODULE_NAME, [
            'slug' => 'appointly-user-history',
            'name' => 'appointment_history_label',
            'href' => admin_url('appointly/appointments_history'),
            'position' => 10,
            'icon' => 'fa fa-history',
        ]);

        $CI->app_menu->add_sidebar_children_item(APPOINTLY_MODULE_NAME, [
            'slug' => 'appointly-callbacks',
            'name' => 'appointly_callbacks',
            'href' => admin_url('appointly/callbacks'),
            'position' => 15,
            'icon' => 'fa fa-phone',
        ]);

        $CI->app_menu->add_sidebar_children_item(APPOINTLY_MODULE_NAME, [
            'slug' => 'appointly-user-settings',
            'name' => 'appointments_your_settings',
            'href' => admin_url('appointly/appointments/user_settings_view/settings'),
            'position' => 20,
            'icon' => 'fa fa-cog',
        ]);

        $CI->app_menu->add_sidebar_children_item(APPOINTLY_MODULE_NAME, [
            'slug' => 'appointly-link-menu-form',
            'name' => 'appointment_menu_form_link',
            'href' => site_url('appointly/appointments_public/form?col=col-md-8+col-md-offset-2'),
            'href_attributes' => 'target="_blank" rel="noopener noreferrer"',
            'position' => 25,
            'icon' => 'fa-brands fa-wpforms',
        ]);

        $CI->app_menu->add_sidebar_children_item(APPOINTLY_MODULE_NAME, [
            'slug' => 'appointly-link-statistics',
            'name' => 'appointment_menu_statistics',
            'href' => admin_url('appointly/appointments/statistics'),
            'position' => 25,
            'icon' => 'fas fa-th-list',
        ]);
        $CI->app_menu->add_sidebar_children_item(APPOINTLY_MODULE_NAME, [
            'slug' => 'appointly-link-booking-page',
            'name' => 'appointment_menu_booking_page',
            'href' => admin_url('appointly/booking_pages'),
            'position' => 25,
            'icon' => 'fa-solid fa-book',
        ]);
        $CI->app_menu->add_setup_menu_item('appointly', [
            'collapse' => true,
            'name' => _l('setup_appointments'),
            'position' => 20,
            'badge' => [],
        ]);
        $CI->app_menu->add_setup_children_item('appointly', [
            'slug' => 'appointly-status',
            'name' => _l('setup_appointments_status'),
            'href' => admin_url('appointly/statuses'),
            'position' => 5,
            'badge' => [],
        ]);
        $CI->app_menu->add_setup_children_item('appointly', [
            'slug' => 'appointly-subjects',
            'name' => _l('setup_appointments_subjects'),
            'href' => admin_url('appointly/subjects'),
            'position' => 5,
            'badge' => [],
        ]);
    }
}

/*
 * Register activation hook
 */
register_activation_hook(APPOINTLY_MODULE_NAME, 'appointly_activation_hook');
register_deactivation_hook(APPOINTLY_MODULE_NAME, 'appointly_deactivation_hook');

/**
 * The activation function.
 */
function appointly_activation_hook()
{
    require __DIR__ . '/activation.php';
}
function appointly_deactivation_hook()
{
    // require __DIR__ . '/deactivation.php';
}
/*
 * Register module language files
 */
register_language_files(APPOINTLY_MODULE_NAME, ['appointly']);

/*
 * Loads the module function helper
 */
$CI->load->helper(APPOINTLY_MODULE_NAME . '/appointly');

/**
 * Register cron email templates.
 */
function appointly_send_email_templates()
{
    $CI = &get_instance();
    $CI->load->model('appointly/appointly_attendees_model', 'atm');

    // User events
    $CI->db->where('(approved = 1 AND finished = 0 AND cancelled = 0)');

    $appointments = $CI->db->get(db_prefix() . 'appointly_appointments')->result_array();
    $notified_users = [];

    $reminder_times = ['10 minutes', '4 hours', '24 hours'];
    foreach ($appointments as $appointment) {
        $date_compare = isset($appointment['reminder_before']) ? (date('Y-m-d H:i', strtotime('+' . $appointment['reminder_before'] . ' ' . strtoupper($appointment['reminder_before_type'])))) : null;

        $date_compare1 = date('Y-m-d H:i', strtotime('+' . $reminder_times[0]));
        $date_compare2 = date('Y-m-d H:i', strtotime('+' . $reminder_times[1]));
        $date_compare3 = date('Y-m-d H:i', strtotime('+' . $reminder_times[2]));
        $last_notification_time = $appointment['last_notification'];
        $send_notification = false;

        if ($last_notification_time == null) {
            $send_notification = ((($date_compare != null) && ($appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare))
                || $appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare1
                || $appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare2
                || $appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare3);
        } else {
            $appointment_time = strtotime($appointment['date'] . ' ' . $appointment['start_hour']);
            $current_time = time();
            $time_remaining = $appointment_time - $current_time;
            $time_notification_sent = $current_time - strtotime($last_notification_time);


            if ($appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare) {
                if ($appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare3) {
                    $send_notification = $time_notification_sent > (24 * 60 * 60) && $time_remaining <= 24 * 60 * 60;// 24 hours
                }
                if ($appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare2) {
                    $send_notification = $time_notification_sent > (4 * 60 * 60) && $time_remaining <= 4 * 60 * 60;// 4 hours
                }
                if ($appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare1) {
                    $send_notification = $time_notification_sent > (10 * 60) && $time_remaining <= 10 * 60;// 10 minutes
                }
            }
            if ($send_notification) {
                if ($appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare) {
                    if (date('Y-m-d H:i', strtotime($appointment['date'] . ' ' . $appointment['start_hour'])) < date('Y-m-d H:i')) {
                        /*
                         * If appointment is missed then skip
                         */
                        continue;
                    }

                    $attendees = $CI->atm->get($appointment['id']);

                    foreach ($attendees as $staff) {
                        add_notification([
                            'description' => 'appointment_you_have_new_appointment',
                            'touserid' => $staff['staffid'],
                            'fromcompany' => true,
                            'link' => 'appointly/appointments/view?appointment_id=' . $appointment['id'],
                        ]);

                        $notified_users[] = $staff['staffid'];

                        send_mail_template('appointly_appointment_cron_reminder_to_staff', 'appointly', array_to_object($appointment), array_to_object($staff));
                    }

                    $template = mail_template('appointly_appointment_cron_reminder_to_contact', 'appointly', array_to_object($appointment));

                    $merge_fields = $template->get_merge_fields();

                    $template->send();

                    if ($appointment['by_sms']) {
                        $CI->app_sms->trigger(APPOINTLY_SMS_APPOINTMENT_APPOINTMENT_REMINDER_TO_CLIENT, $appointment['phone'], $merge_fields);
                    }

                    $CI->db->where('id', $appointment['id']);
                    $CI->db->update('appointly_appointments', ['notification_date' => date('Y-m-d H:i:s')]);
                }
            }
        }
    }
    pusher_trigger_notification(array_unique($notified_users));
}
/**
 * Register cron email templates.
 */
function appointly_send_email_templates_auto($manually)
{
    return;
    $CI = &get_instance();
    $CI->load->model('appointly/appointly_attendees_model', 'atm');

    // User events
    $CI->db->where('(finished = 0 AND cancelled = 0 AND booking_page_id IS NOT NULL)');

    $appointments = $CI->db->get(db_prefix() . 'appointly_appointments')->result_array();
    log_message('error', 'appointly_send_email_templates_auto ' . count($appointments));
    $notified_users = [];

    $reminder_times = ['10 minutes', '4 hours', '24 hours'];
    foreach ($appointments as $appointment) {
        $date_compare1 = date('Y-m-d H:i', strtotime('+' . $reminder_times[0]));
        $date_compare2 = date('Y-m-d H:i', strtotime('+' . $reminder_times[1]));
        $date_compare3 = date('Y-m-d H:i', strtotime('+' . $reminder_times[2]));

        if (
            $appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare1
            || $appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare2
            || $appointment['date'] . ' ' . $appointment['start_hour'] <= $date_compare3
        ) {
            if (date('Y-m-d H:i', strtotime($appointment['date'] . ' ' . $appointment['start_hour'])) < date('Y-m-d H:i')) {
                /*
                 * If appointment is missed then skip
                 */
                continue;
            }

            $attendees = $CI->atm->get($appointment['id']);

            foreach ($attendees as $staff) {
                add_notification([
                    'description' => 'appointment_you_have_new_appointment',
                    'touserid' => $staff['staffid'],
                    'fromcompany' => true,
                    'link' => 'appointly/appointments/view?appointment_id=' . $appointment['id'],
                ]);

                $notified_users[] = $staff['staffid'];

                send_mail_template('appointly_appointment_cron_reminder_to_staff', 'appointly', array_to_object($appointment), array_to_object($staff));
            }

            $template = mail_template('appointly_appointment_cron_reminder_to_contact', 'appointly', array_to_object($appointment));

            $merge_fields = $template->get_merge_fields();

            $template->send();

            if ($appointment['by_sms']) {
                $CI->app_sms->trigger(APPOINTLY_SMS_APPOINTMENT_APPOINTMENT_REMINDER_TO_CLIENT, $appointment['phone'], $merge_fields);
            }

            $CI->db->where('id', $appointment['id']);
            $CI->db->update('appointly_appointments', ['notification_date' => date('Y-m-d H:i:s')]);
        }
    }
    pusher_trigger_notification(array_unique($notified_users));
}



function appointly_recurring_events()
{
    $CI = &get_instance();
    $tableAttendees = db_prefix() . 'appointly_attendees';
    $table = db_prefix() . 'appointly_appointments';

    // User events
    $CI->db->where('recurring', 1);
    $CI->db->where('(cycles != total_cycles OR cycles=0)');

    $appointments = $CI->db->get(db_prefix() . 'appointly_appointments')->result_array();


    $responsiblePerson = get_option('appointly_responsible_person');


    foreach ($appointments as $appointment) {
        $type = $appointment['recurring_type'];
        $repeat_every = $appointment['repeat_every'];
        $last_recurring_date = $appointment['last_recurring_date'];

        $appointment_date = $appointment['date'];
        // Current date
        $date = new DateTime(date('Y-m-d'));
        // Check if is first recurring
        if (!$last_recurring_date) {
            $last_recurring_date = date('Y-m-d', strtotime($appointment_date));
        } else {
            $last_recurring_date = date('Y-m-d', strtotime($last_recurring_date));
        }

        $re_create_at = date('Y-m-d', strtotime('+' . $repeat_every . ' ' . strtoupper($type), strtotime($last_recurring_date)));

        if (date('Y-m-d') >= $re_create_at) {

            // Ok we can repeat the appointment now
            $newAppointmentData = [];

            $newAppointmentData['date'] = $re_create_at;

            $newAppointmentData = array_merge($newAppointmentData, convertDateForDatabase($newAppointmentData['date']));

            $newAppointmentData['google_event_id'] = null;
            $newAppointmentData['google_calendar_link'] = null;
            $newAppointmentData['google_meet_link'] = null;
            $newAppointmentData['google_added_by_id'] = $appointment['google_added_by_id'];
            $newAppointmentData['outlook_event_id'] = $appointment['outlook_event_id'];
            $newAppointmentData['outlook_calendar_link'] = $appointment['outlook_calendar_link'];
            $newAppointmentData['outlook_added_by_id'] = $appointment['outlook_added_by_id'];
            $newAppointmentData['subject'] = $appointment['subject'];
            $newAppointmentData['description'] = $appointment['description'];
            $newAppointmentData['email'] = $appointment['email'];
            $newAppointmentData['name'] = $appointment['name'];
            $newAppointmentData['phone'] = $appointment['phone'];
            $newAppointmentData['address'] = $appointment['address'];
            $newAppointmentData['notes'] = $appointment['notes'];
            $newAppointmentData['contact_id'] = $appointment['contact_id'];
            $newAppointmentData['by_sms'] = $appointment['by_sms'];
            $newAppointmentData['by_email'] = $appointment['by_email'];
            $newAppointmentData['hash'] = app_generate_hash();
            $newAppointmentData['start_hour'] = $appointment['start_hour'];
            $newAppointmentData['approved'] = 1;
            $newAppointmentData['created_by'] = $appointment['created_by'];
            $newAppointmentData['reminder_before'] = $appointment['reminder_before'];
            $newAppointmentData['reminder_before_type'] = $appointment['reminder_before_type'];
            $newAppointmentData['cancel_notes'] = $appointment['cancel_notes'];
            $newAppointmentData['source'] = $appointment['source'];
            $newAppointmentData['feedback_comment'] = $appointment['feedback_comment'];
            $newAppointmentData['recurring_type'] = null;
            $newAppointmentData['repeat_every'] = 0;
            $newAppointmentData['recurring'] = 0;
            $newAppointmentData['cycles'] = 0;
            $newAppointmentData['total_cycles'] = 0;
            $newAppointmentData['custom_recurring'] = 0;
            $newAppointmentData['last_recurring_date'] = null;


            $newAppointmentData = handleDataReminderFields($newAppointmentData);

            $CI->db->insert($table, $newAppointmentData);

            $insert_id = $CI->db->insert_id();

            if ($insert_id) {
                // Get the old appointment custom field and add to the new
                $fieldTo = 'appointly';
                $custom_fields = get_custom_fields($fieldTo);

                foreach ($custom_fields as $field) {

                    $value = get_custom_field_value($appointment['id'], $field['id'], $fieldTo, false);

                    if ($value != '') {
                        $CI->db->insert(db_prefix() . 'customfieldsvalues', [
                            'relid' => $insert_id,
                            'fieldid' => $field['id'],
                            'fieldto' => $fieldTo,
                            'value' => $value,
                        ]);
                    }
                }

                // update recurring date for original appointment
                $CI->db->where('id', $appointment['id']);
                $CI->db->update($table, ['last_recurring_date' => $re_create_at]);

                // set total_cycles +1 for original appointment
                $CI->db->where('id', $appointment['id']);
                $CI->db->set('total_cycles', 'total_cycles+1', false);
                $CI->db->update($table);


                $googleAttendees = [];

                // insert attendees for new appointment
                $originalAttendees = $CI->db->where('appointment_id', $appointment['id'])->get($tableAttendees)->result_array();

                foreach ($originalAttendees as &$attendee) {
                    $googleAttendees[] = $attendee['staff_id'];
                    $attendee['appointment_id'] = $insert_id;
                }

                $CI->db->insert_batch($tableAttendees, $originalAttendees);


                // google calendar
                if ($appointment['google_event_id'] != '') {

                    $CI->load->model('appointly/appointly_model');

                    $lastInsertedAppointment = $CI->db->where('id', $insert_id)->get($table)->row_array();

                    $googleInsertData = $CI->appointly_model->recurringAddGoogleNewEvent($lastInsertedAppointment, $googleAttendees);

                    if (!empty($googleInsertData)) {
                        // update appointment wih new google event data
                        $CI->db->where('id', $insert_id);
                        $CI->db->update($table, $googleInsertData);
                    }
                }

                newRecurringAppointmentNotifications($insert_id);

                if (!empty($responsiblePerson)) {

                    add_notification([
                        'description' => 'appointment_recurring_re_created',
                        'touserid' => $responsiblePerson,
                        'fromcompany' => true,
                        'link' => 'appointly/appointments/view?appointment_id=' . $insert_id,
                    ]);

                    pusher_trigger_notification([$responsiblePerson]);
                }
            }
        }
    }
}

hooks()->add_filter('sms_gateway_available_triggers', 'appointly_register_sms_triggers');
/**
 * Register SMS Triggers for appointly.
 *
 * @param [array] $triggers
 *
 * @return array
 */
function appointly_register_sms_triggers($triggers)
{
    $triggers[APPOINTLY_SMS_APPOINTMENT_APPROVED_TO_CLIENT] = [
        'merge_fields' => [
            '{appointment_subject}',
            '{appointment_date}',
            '{appointment_client_name}',
        ],
        'label' => 'Appointment approved (Sent to Contact)',
        'info' => 'Trigger when appointment is approved, SMS will be sent to the appointment contact number.',
    ];

    $triggers[APPOINTLY_SMS_APPOINTMENT_CANCELLED_TO_CLIENT] = [
        'merge_fields' => [
            '{appointment_subject}',
            '{appointment_date}',
            '{appointment_client_name}',
        ],
        'label' => 'Appointment cancelled (Sent to Contact)',
        'info' => 'Trigger when appointment is cancelled, SMS will be sent to the appointment contact number.',
    ];

    $triggers[APPOINTLY_SMS_APPOINTMENT_APPOINTMENT_REMINDER_TO_CLIENT] = [
        'merge_fields' => [
            '{appointment_subject}',
            '{appointment_date}',
            '{appointment_client_name}',
        ],
        'label' => 'Appointment reminder (Sent to Contact)',
        'info' => 'Trigger when reminder before date is set when appointment is created, SMS will be sent to the appointment contact number.',
    ];

    return $triggers;
}

/*
 * Check if can have permissions then apply new tab in settings
 */
if (staff_can('view', 'settings')) {
    hooks()->add_action('admin_init', 'appointly_add_settings_tab');
}

function appointly_add_settings_tab()
{
    $CI = &get_instance();
    $CI->app_tabs->add_settings_tab('appointly-settings', [
        'name' => _l('appointment_appointments'),
        'view' => 'appointly/settings',
        'position' => 36,
    ]);
}

/*
 * Need to change encode array values to string for database before post
 * Intercepting settings-form
 * @return array
 */
hooks()->add_filter('before_settings_updated', 'modify_settings_form_post');

function modify_settings_form_post($form)
{
    if (isset($form['settings']['appointly_available_hours'])) {
        $form['settings']['appointly_available_hours'] = json_encode($form['settings']['appointly_available_hours']);
        if ($form['settings']['appointly_available_hours'] == null) {
            $form['settings']['appointly_available_hours'] = json_encode([]);
        }
    }
    if (isset($form['settings']['appointly_default_feedbacks'])) {
        $form['settings']['appointly_default_feedbacks'] = json_encode($form['settings']['appointly_default_feedbacks']);
        if ($form['settings']['appointly_default_feedbacks'] == null) {
            $form['settings']['appointly_default_feedbacks'] = json_encode([]);
        }
    }

    return $form;
}
function runextraquery()
{
    $CI->db->query(
        "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_appointments_statuses (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` varchar(191) DEFAULT NULL,
            `description` varchar(191) DEFAULT NULL,
            `is_active` bit DEFAULT b'0',           
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
    );
    try {
        // Add a new column with an optional relationship
        $CI->db->query(
            "ALTER TABLE " . db_prefix() . "appointly_appointments 
        ADD COLUMN `status_id` int(11) UNSIGNED DEFAULT NULL;"
        );

        // Optionally, add a foreign key constraint (uncomment if you need a foreign key)
        $CI->db->query(
            "ALTER TABLE " . db_prefix() . "appointly_appointments 
        ADD CONSTRAINT `fk_status_id` FOREIGN KEY (`status_id`) 
        REFERENCES " . db_prefix() . "appointly_appointments_statuses(`id`) ON DELETE SET NULL ON UPDATE CASCADE;"
        );
    } catch (Exception $e) {
    }

    try {


        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "appointly_booking_pages (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(191) DEFAULT NULL,
                `description` varchar(191) DEFAULT NULL,
                `url` varchar(191) DEFAULT NULL,
                `duration_minutes` varchar(191) DEFAULT NULL,
                `simultaneous_appointments` int(11) UNSIGNED DEFAULT 1,
                `appointly_responsible_person` int(11) DEFAULT NULL,
                `callbacks_responsible_person` int(11) DEFAULT NULL,
                `appointly_available_hours` varchar(191) DEFAULT NULL,
                `appointly_default_feedbacks` varchar(191) DEFAULT NULL,
                `google_api_key` varchar(191) DEFAULT NULL,
                `google_client_id` varchar(191) DEFAULT NULL,
                `appointly_google_client_secret` varchar(191) DEFAULT NULL,
                `appointly_outlook_client_id` varchar(191) DEFAULT NULL,
                `appointly_appointments_recaptcha` bit DEFAULT NULL,
                `appointly_busy_times_enabled` bit DEFAULT NULL,
                `appointly_also_delete_in_google_calendar` bit DEFAULT NULL,
                `appointments_disable_weekends` bit DEFAULT NULL,
                `appointly_view_all_in_calendar` bit DEFAULT NULL,
                `appointly_client_meeting_approved_default` bit DEFAULT 0,
                `appointly_tab_on_clients_page` bit DEFAULT 0,
                `appointly_show_clients_schedule_button` bit DEFAULT 0,
                `appointments_show_past_times` bit DEFAULT 0,
                `callbacks_mode_enabled` bit DEFAULT 0,
                `is_active` bit DEFAULT b'1',           
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        // Create the subjects table
        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS  " . db_prefix() . "appointly_appointments_subjects (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `subject` varchar(191) DEFAULT NULL,  
                `booking_page_id` int(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`booking_page_id`) REFERENCES  " . db_prefix() . "appointly_booking_pages(`id`) 
                ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        // Add a new column with an optional relationship
        $CI->db->query(
            "ALTER TABLE " . db_prefix() . "appointly_appointments 
                ADD COLUMN `booking_page_id` int(11) UNSIGNED DEFAULT NULL;"
        );

        // Optionally, add a foreign key constraint (uncomment if you need a foreign key)
        $CI->db->query(
            "ALTER TABLE " . db_prefix() . "appointly_appointments 
                ADD CONSTRAINT `fk_booking_page_id` FOREIGN KEY (`booking_page_id`) 
                REFERENCES " . db_prefix() . "appointly_booking_pages(`id`) ON DELETE SET NULL ON UPDATE CASCADE;"
        );

    } catch (Exception $e) {
    }

}
