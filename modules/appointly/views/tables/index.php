<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'appointly_appointments.id as id',
    'subject',
    'CAST(CONCAT(date, \' \', start_hour) AS DATETIME) as date',
    'firstname as creator_firstname',
    'status_id'
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'appointly_appointments';

$where = [];

if (!is_admin() && !staff_appointments_responsible()) {
    array_push($where, 'AND (' . db_prefix() . 'appointly_appointments.created_by=' . get_staff_user_id() . ')
    OR ' . db_prefix() . 'appointly_appointments.id
    IN (SELECT appointment_id FROM ' . db_prefix() . 'appointly_attendees WHERE staff_id=' . get_staff_user_id() . ')');
}
$filters = [];
if ($this->ci->input->post('approved')) {
    $filters[] = 'AND approved = 1';
}
if ($this->ci->input->post('cancelled')) {
    $filters[] = 'AND cancelled = 1';
}
if ($this->ci->input->post('finished')) {
    $filters[] = 'AND finished = 1';
}
if ($this->ci->input->post('status_id')) {
    $filters[] = 'AND status_id = ' . (int) $this->ci->input->post('status_id');
}
if ($this->ci->input->post('internal')) {
    $filters[] = 'AND (source= "internal")';
}
if ($this->ci->input->post('external')) {
    $filters[] = 'AND (source= "external")';
}
if ($this->ci->input->post('lead_related')) {
    $filters[] = 'AND (source= "lead_related")';
}
if ($this->ci->input->post('booking_page')) {
    $filters[] = 'AND (source= "booking_page")';
}
if ($this->ci->input->post('internal_staff')) {
    $filters[] = 'AND (source= "internal_staff_crm")';
}
// if ($this->ci->input->post('finished')) {
//     $filters[] = 'AND finished = 1';
// }
if ($this->ci->input->post('not_approved')) {
    $filters[] = 'AND approved != 1';
}
if ($this->ci->input->post('upcoming')) {
    $filters[] = 'AND date > CURDATE()';
}
if ($this->ci->input->post('missed')) {
    $filters[] = 'AND date < CURDATE()';
}
if ($this->ci->input->post('recurring')) {
    $filters[] = 'AND recurring = 1';
}

if (count($filters) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filters) . ')');
}

$join = [
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'appointly_appointments.created_by',
    'LEFT JOIN ' . db_prefix() . 'appointly_appointments_statuses ON ' . db_prefix() . 'appointly_appointments.status_id = ' . db_prefix() . 'appointly_appointments_statuses.id',
];

$additionalSelect = [
    'approved',
    'created_by',
    'lastname as creator_lastname',
    db_prefix() . 'appointly_appointments.name as name',
    db_prefix() . 'appointly_appointments.email as contact_email',
    db_prefix() . 'appointly_appointments.phone',
    db_prefix() . 'appointly_appointments_statuses.name as status_name',
    'cancelled',
    'contact_id',
    'google_calendar_link',
    'google_added_by_id',
    'outlook_calendar_link',
    'outlook_added_by_id',
    'outlook_event_id',
    'feedback',
    'finished',
    'source',
    db_prefix() . 'appointly_appointments.description as description',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $label_class = 'primary';
    $tooltip = '';

    // Check with Perfex CRM default timezone configured in Setup->Settings->Localization
    if (date('Y-m-d H:i', strtotime($aRow['date'])) < date('Y-m-d H:i')) {
        $label_class = 'danger';
        $tooltip = 'data-toggle="tooltip" title="' . _l('appointment_missed') . '"';
    }

    $row = [];
    $col1 = '';
    $hrefAttr = 'data-toggle="tooltip" title="' . _l('appointment_view_meeting') . '" href="' . admin_url('appointly/appointments/view?appointment_id=' . $aRow['id']) . '"';
    $col1 .= '#' . $aRow['id'] . '<br>';

    $col1 .= '<br><a href="' . admin_url('appointly/appointments/view?appointment_id=' . $aRow['id']) . '">' . $aRow['name'] . '</a>';
    $col1 .= '<br><br><a href="mailto:' . $aRow['contact_email'] . '">' . $aRow['contact_email'] . '</a>';
    $col1 .= '<br><br><a href="tel:' . $aRow['phone'] . '">' . $aRow['phone'] . '</a><br><br>';

    if ($aRow['approved'] && $aRow['cancelled'] == 0) {
        $col1 .= '<p class="text-success no-mbot">' . _l('appointment_approved') . '</p>';
    }

    $col1 .= '<div class="row-options no-mtop">';
    $col1 .= '<a ' . $hrefAttr . '>' . _l('view') . '</a>';
    if (
        $aRow['approved'] == 0
        && is_admin() && $aRow['cancelled'] == 0
        || $aRow['approved'] == 0
        && staff_can('view', 'appointments')
        && $aRow['cancelled'] == 0
    ) {
        $col1 .= ' | <a class="approve_appointment" href="' . admin_url('appointly/appointments/approve?appointment_id=' . $aRow['id']) . '">' . _l('appointment_approve') . '</a>';
    }
    if (staff_can('edit', 'appointments') || staff_appointments_responsible()) {
        if ($aRow['source'] != 'internal_staff_crm') {
            $col1 .= ' | <a href="" data-toggle="tooltip" title="' . _l('appointment_edit_meeting') . '" data-id="' . $aRow['id'] . '" onclick="appointmentUpdateModal(this); return false;">' . _l('edit') . '</a>';
        } else {
            $col1 .= ' | <a href="" data-toggle="tooltip" title="' . _l('appointment_edit_meeting') . '" data-id="' . $aRow['id'] . '" onclick="appointmentGlobalStaffModal(this); return false;">' . _l('edit') . '</a>';
        }
    }
    // If contact id is not 0 then it means that contact is internal as for that dont show convert to lead
    $isContact = ($aRow['contact_id']) ? 0 : 1;

    // convert to task
    $col1 .= (staff_can('create', 'tasks') && $aRow['approved'] == 1 && $aRow['source'] != 'internal_staff_crm') ?
        ' | <a data-toggle="tooltip" title="' . _l('appointments_create_task_tooltip') . '" href="#" data-customer-id="' . appointly_get_contact_customer_id($aRow['contact_id']) . '" data-source="' . $aRow['source'] . '" data-contact-id="' . $aRow['contact_id'] . '" data-name="' . $aRow['name'] . '" onclick="new_task_from_relation_appointment(this); return false;">' . _l('new_task') . '</a>'
        : '';

    // convert to lead
    $col1 .= ($isContact && $aRow['approved'] == 1 && $aRow['source'] != 'internal_staff_crm') ?
        ' | <a data-toggle="tooltip" title="' . _l('appointments_convert_to_lead_tooltip') . '" href="#" data-name="' . $aRow['name'] . '" data-email="' . $aRow['contact_email'] . '" data-phone="' . $aRow['phone'] . '" onclick="init_appointment_lead(this);return false;">' . _l("appointments_convert_to_lead_label") . '</a>'
        : '';

    // If there is no feedback from client and if appintment is marked as finished
    if ($aRow['feedback'] !== null && $aRow['finished'] !== 1) {
        $col1 .= ' | <a data-toggle="tooltip" title="' . _l('appointment_view_feedback') . '" href="' . admin_url('appointly/appointments/view?appointment_id=' . $aRow['id']) . '#feedback_wrapper">' . _l('appointment_view_feedback') . '</a></li>';
    } else if ($aRow['finished'] == 1) {
        $col1 .= ' | <a onclick="request_appointment_feedback(\'' . $aRow['id'] . '\'); return false" data-toggle="tooltip" title="' . _l('appointments_request_feedback_from_client') . '" href="">' . _l('appointments_request_feedback') . '</a>';
    }

    if (staff_can('delete', 'appointments') && $aRow['created_by'] == get_staff_user_id() || staff_appointments_responsible()) {
        $col1 .= ' | <a id="confirmDelete" data-toggle="tooltip" class="text-danger" title="' . _l('appointment_dismiss_meeting') . '" href="" onclick="deleteAppointment(' . $aRow['id'] . ',this); return false;">' . _l('delete') . '</a>';
    }

    $col1 .= '</div>';

    $row[] = $col1;
    $col2 = '<br><a href="' . admin_url('appointly/appointments/view?appointment_id=' . $aRow['id']) . '">' . $aRow['subject'] . '</a>';
    $col2 .= '<br><br>' . $aRow['description'] . '';
    $row[] = $col2;
    $col3 = '<span  ' . $tooltip . ' class="label label-' . $label_class . '">' . _dt($aRow['date']) . '</span>';

    $col3 .= '<br><br>' . $aRow['name'];

    $row[] = $col3;
    $col4 = '';
    if ($aRow['creator_firstname']) {
        $staff_fullname = $aRow['creator_firstname'] . ' ' . $aRow['creator_lastname'];

        $col4 .= '<a class="initiated_by" target="_blank" href="' . admin_url() . "profile/" . $aRow["created_by"] . '"><img src="' . staff_profile_image_url($aRow["created_by"], "small") . '" data-toggle="tooltip" data-title="' . $staff_fullname . '" class="staff-profile-image-small mright5" data-original-title="" title="' . $staff_fullname . '">' . $staff_fullname . '</a><br><br>';
    }

    if ($aRow['source'] == 'external') {
        $col4 .= _l('appointments_source_external_label');
    }
    if ($aRow['source'] == 'internal') {
        $col4 .= _l('appointments_source_internal_label');
    }
    if ($aRow['source'] == 'lead_related') {
        $col4 .= _l('lead');
    }
    if ($aRow['source'] == 'internal_staff_crm') {
        $col4 .= _l('appointment_internal_staff');
    }
    if ($aRow['source'] == 'booking_page') {
        $col4 .= _l('appointment_by_booking_page');
    }

    $row[] = $col4;

    if (staff_can('edit', 'appointments') || staff_can('create', 'appointments') || staff_appointments_responsible()) {
        $currentStatus = checkAppointlyStatus($aRow);

        $outputStatus = '<div class="dropdown inline-block mleft5">';
        $outputStatus .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="appointmentStatusesDropdown' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';

        $outputStatus .= checkAppointlyStatus($aRow);

        $outputStatus .= '</a>';

        $customStatuses = get_statuses();
        $statusHtml = '';
        foreach ($customStatuses as $status) {
            if ($status['id'] != $aRow['status_id']) {
                $statusHtml .= '<li><a href="" onclick="markAppointmentStatus(' . $aRow['id'] . ', ' . $status['id'] . '); return false" href="">' . _l('task_mark_as', $status['name']) . '</a></li>';
            }
        }
        if ($aRow['finished'] != 1) {
            $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="appointmentStatusesDropdown' . $aRow['id'] . '">';
        }
        $needs_approval = $aRow['approved'] == 0 && $aRow['cancelled'] == 0 && is_admin() || $aRow['approved'] == 0 && $aRow['cancelled'] == 0 && staff_can('view', 'appointments');

        if ($needs_approval) {
            $outputStatus .= '<li><a href="" onclick="markAppointmentAsApproved(' . $aRow['id'] . '); return false" href="">' . _l('task_mark_as', 'Approved') . '</a></li>';
        }

        if ($aRow['cancelled'] == 0 && $aRow['finished'] == 0) {
            if ($aRow['created_by'] == get_staff_user_id() || staff_appointments_responsible()) {
                $outputStatus .= '<li><a href="" onclick="markAppointmentAsCancelled(' . $aRow['id'] . '); return false" id-"cancelAppointment">' . _l('task_mark_as', 'Cancelled') . '</a></li>' . $statusHtml;
            }
        }

        if ($aRow['finished'] == 0 && $aRow['cancelled'] == 0 && $aRow['approved'] != 0) {
            if ($aRow['created_by'] == get_staff_user_id() || staff_appointments_responsible()) {
                $outputStatus .= '<li><a href="" onclick="markAppointmentAsFinished(' . $aRow['id'] . '); return false" id="markAsFinished">' . _l('task_mark_as', 'Finished') . '</a></li>';
            }
        }

        if ($aRow['cancelled'] == 1 && $aRow['finished'] == 0) {
            if ($aRow['created_by'] == get_staff_user_id() || staff_appointments_responsible()) {
                $outputStatus .= '<li><a href="" onclick="markAppointmentAsOngoing(' . $aRow['id'] . '); return false" id-"markAppointmentAsOngoing">' . _l('task_mark_as', 'Ongoing') . '</a></li>';
            }
        }
        $outputStatus .= '</ul>';
        $outputStatus .= '</div>';
        $outputStatus .= '</span>';
    } else {
        $outputStatus = '<div>';
        $outputStatus .= '<a data-toggle="tooltip" title="' . $currentStatus . '" href="#" style="font-size:14px;vertical-align:middle;cursor:context-menu;" class="text-dark">';
        $outputStatus .= '<span class="label label-callback-status-' . $currentStatus . '">' . $currentStatus . '</span>';
        $outputStatus .= '</a>';
        $outputStatus .= '</div>';
    }

    $row[] = $outputStatus;



    $options = '';
    $_google_calendar_link = $aRow['google_calendar_link'] !== null && $aRow['google_added_by_id'] == get_staff_user_id();
    $_outlook_calendar_link = $aRow['outlook_calendar_link'] !== null && $aRow['outlook_added_by_id'] == get_staff_user_id();

    $options .= '<div class="text-center">';

    if ($_google_calendar_link) {
        $options .= '<a data-toggle="tooltip" title="' . _l('appointment_open_google_calendar') . '" href="' . $aRow['google_calendar_link'] . '" target="_blank" class="mleft10 calendar_list"><i class="fa-brands fa-google" aria-hidden="true"></i></a>';
    }

    if ($_outlook_calendar_link) {
        $options .= '<a data-outlook-id="' . $aRow['outlook_event_id'] . '" id="outlookLink_' . $aRow['id'] . '" data-toggle="tooltip" title="' . _l('appointment_open_outlook_calendar') . '" href="' . $aRow['outlook_calendar_link'] . '" target="_blank" class="mleft5 calendar_list float-right"><i class="fa-regular fa-envelope" aria-hidden="true"></i></a>';
    }
    if (!$_google_calendar_link && !$_outlook_calendar_link) {
        $options .= '<p class="text-muted">Not added to any calendar yet.</p>'; //lang
    }

    $options .= '</div>';

    $row['DT_RowId'] = 'appointment_id' . $aRow['id'];

    if (isset($row['DT_RowClass'])) {
        $row['DT_RowClass'] .= ' has-row-options';
    } else {
        $row['DT_RowClass'] = 'has-row-options';
    }

    // $row[] = $options;

    $output['aaData'][] = $row;
}
