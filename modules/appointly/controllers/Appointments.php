<?php defined('BASEPATH') or exit('No direct script access allowed');

class Appointments extends AdminController
{
    private $staff_no_view_permissions;

    public function __construct()
    {
        parent::__construct();

        $this->staff_no_view_permissions = !staff_can('view', 'appointments') && !staff_can('view_own', 'appointments');

        $this->load->model('appointly_model', 'apm');
        $this->load->model('leads_model');
        $this->load->model('booking_page_model');
        $this->load->database();
    }

    /**
     * Main view
     *
     * @return void
     */
    public function index()
    {
        if ($this->staff_no_view_permissions) {
            access_denied('Appointments');
        }

        $data['td_appointments'] = $this->getTodaysAppointments();

        $this->load->view('index', $data);
    }

    public function get_lead_profile_content($lead_id)
    {
        // Fetch data from the database or model
        $data['td_appointments'] = $this->apm->fetch_lead_appointments($lead_id);
        
        // Load the view content into a variable
        $view_content = $this->load->view('appointly/lead_profile_content', $data, TRUE);

        // Return the view content as JSON response
        echo json_encode(['content' => $view_content]);
    }
    public function statistics()
    {
        if ($this->staff_no_view_permissions) {
            access_denied('Appointments');
        }
        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
        } else {
            $end_date =  date('Y-m-d');

            // Subtract 30 days from today's date
            $thirtyDaysAgo = (new DateTime())->sub(new DateInterval('P30D'));

            // Format the date as YYYY-MM-DD
            $start_date = $thirtyDaysAgo->format('Y-m-d');
        }
      
        $att = isset($_GET['attendees']) ? $_GET['attendees'] : [];
        $attendees = $att; //implode(',',$att);

        $att = isset($_GET['booking_pages']) ? $_GET['booking_pages'] : [];
        $booking_pages = $att; //implode(',',$att);
      
        // Create DateTime objects
        $startDateTime = new DateTime($start_date);
        $endDateTime = new DateTime($end_date);

        // Calculate the difference
        $interval = $startDateTime->diff($endDateTime);

        // Get the difference in days
        $daysDifference = $interval->days;


        $sql_internal = 'SELECT * FROM 
            `'. db_prefix() .'appointly_appointments` 
            WHERE date >= ? AND date <= ?';
        $sql_internal2 = "SELECT * FROM 
            `". db_prefix() ."appointly_appointments` 
            WHERE date >= DATE_SUB(?, INTERVAL " . $daysDifference . " DAY) AND date <= DATE_SUB(?, INTERVAL " . $daysDifference . " DAY)";
        if (count($attendees) > 0) {
            $sql_internal .= " AND id IN (SELECT a.appointment_id from ". db_prefix() ."appointly_attendees a WHERE a.staff_id IN (" . implode(',',$attendees) . ") )";
            $sql_internal2 .= " AND id IN (SELECT a.appointment_id from " . db_prefix() . "appointly_attendees a WHERE a.staff_id IN (" . implode(',',$attendees) . ") )";
        }
        if (count($booking_pages) > 0) {
            $sql_internal .= " AND booking_page_id IN (" . implode(',',$booking_pages) . ")";
            $sql_internal2 .= " AND booking_page_id IN (" . implode(',',$booking_pages) . ")";
        }
        $sql = "SELECT 
            COUNT(*) total_filtered,
            IFNULL(SUM(CASE WHEN  a.finished = 1 THEN 1 ELSE 0 END),0) AS completed_filtered,
            IFNULL(SUM(CASE WHEN  a.cancelled = 1 THEN 1 ELSE 0 END),0) AS cancelled_filtered,
            '" . $start_date . "' AS first_date_filtered,
            '" . $end_date . "' AS last_date_filtered
            FROM
            (" . $sql_internal . ") a";


        $sql2 = "SELECT 
            IFNULL(COUNT(*),0) AS total_prior_filtered,
            IFNULL(SUM(CASE WHEN a.finished = 1 THEN 1 ELSE 0 END),0) AS completed_prior_filtered,
            IFNULL(SUM(CASE WHEN a.cancelled = 1 THEN 1 ELSE 0 END),0) AS cancelled_prior_filtered,
            DATE_SUB('" . $start_date . "', INTERVAL " . $daysDifference . " DAY)  AS first_date_prior_filtered,
            DATE_SUB('" . $end_date . "', INTERVAL " . $daysDifference . " DAY) AS last_date_prior_filtered
            FROM
            (" . $sql_internal2 . ") a";

        $query2 = $this->db->query($sql2, [
            $start_date, $end_date   // Base date for prior range calculations
        ]);
        $query = $this->db->query($sql, [
            $start_date, $end_date   // Base date for prior range calculations
        ]);

        $sql_completed_by_dates = "WITH RECURSIVE dates_cte AS (
                SELECT DATE('" . $start_date . "') AS date
                UNION ALL
                SELECT date + INTERVAL 1 DAY
                FROM dates_cte
                WHERE date < '" . $end_date . "'
            )
            SELECT 
                d.date,
                COUNT(a.date) AS completed_appointments
            FROM 
                dates_cte d
            LEFT JOIN 
                ". db_prefix() ."appointly_appointments a ON d.date = a.date AND a.finished = 1
            GROUP BY 
                d.date
            ORDER BY 
                d.date;";
        $sql_internal_attendees = "SELECT * FROM 
            ". db_prefix() ."appointly_appointments 
            WHERE date >= ? AND date <= ? AND finished = 1";
        $sql_attendees_appointments = "SELECT s.staffid, CONCAT(s.firstname,' ', s.lastname) name, COUNT(att.staff_id) appointments FROM `tblstaff` s
            LEFT JOIN " . db_prefix() . "appointly_attendees att ON att.staff_id = s.staffid
            LEFT JOIN (" . $sql_internal_attendees . ") AS ap ON ap.id = att.appointment_id
            GROUP By att.staff_id;";

        $data['completed_by_staff'] = ($this->db->query($sql_attendees_appointments, [
            $start_date, $end_date   // Base date for prior range calculations
        ]))->result_array();
        $data['completed_by_date'] = ($this->db->query($sql_completed_by_dates))->result_array();
        $data['summary'] = $query->result_array();
        $data['summary2'] = $query2->result_array();
        $data['prior_days'] = $daysDifference;
        $data['attendees'] =$attendees;// explode(',', $attendees);
        $data['selected_booking_pages'] = $booking_pages;//explode(',', $booking_pages);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['staff'] = $this->staff_model->get('', ['active' => 1]);
        $data['booking_pages'] = $this->booking_page_model->get_all();

        $this->load->view('statistics', $data);
    }

    /**
     * Single appointment view
     *
     * @return void
     */
    public function view()
    {
        if ($this->staff_no_view_permissions) {
            access_denied('Appointments');
        }
        $this->session->unset_userdata('from_view_id');

        $appointment_id = $this->input->get('appointment_id');

        $attendees = $this->atm->attendees($appointment_id);
        /**
         * If user is assigned to a appointment but have no permissions at all eg. edit or view
         * User will be able to open the url send to mail (But only to view this specific meeting or meetings that the user is assigned to)
         */

        if (!in_array(get_staff_user_id(), $attendees)) {
            // Global view permissions required
            if (!staff_can('view', 'appointments')) {
                access_denied('Appointments');
            }
        }

        $data['appointment'] = fetch_appointment_data($appointment_id);

        if ($data['appointment']) {
            $data['appointment']['public_url'] = site_url('appointly/appointments_public/client_hash?hash=' . $data['appointment']['hash']);
        } else {
            appointly_redirect_after_event('warning', _l('appointment_not_exists'));
        }

        if (!$data['appointment']) {
            show_404();
        }

        $this->load->view('tables/appointment', $data);
    }

    /**
     * Render table view
     *
     * @return void
     */
    public function table()
    {
        if ($this->staff_no_view_permissions) {
            access_denied();
        }

        $this->app->get_table_data(module_views_path(APPOINTLY_MODULE_NAME, 'tables/index'));
    }

    /**
     * Get contact data
     *
     * @return void
     */
    public function fetch_contact_data()
    {
        if (!$this->input->is_ajax_request() || !is_staff_logged_in()) {
            show_404();
        }

        $id = $this->input->post('contact_id');
        $is_lead = $this->input->post('lead');

        if ($id) {
            header('Content-Type: application/json');
            echo json_encode($this->apm->apply_contact_data($id, $is_lead));
        }
    }

    /**
     * Modal edit and modal update trigger views with data
     *
     * @return void
     */
    public function modal()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);

        $data['contacts'] = appointly_get_staff_customers();

        if ($this->input->post('slug') === 'create') {

            $this->load->view('modals/create', $data);
        } else if ($this->input->post('slug') === 'update') {

            $data['appointment_id'] = $this->input->post('appointment_id');

            $data['history'] = fetch_appointment_data($data['appointment_id']);

            if (isset($data['notes'])) {
                $data['notes'] = htmlentities($data['notes']);
            }

            $this->load->view('modals/update', $data);
        }
    }

    /**
     * Modal edit and modal update trigger views with data
     *
     * @return void
     */
    public function modal_internal_crm()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);

        if ($this->input->post('slug') === 'create') {
            $this->load->view('modals/create_internal_crm', $data);
        } else {
            $data['appointment_id'] = $this->input->post('appointment_id');
            $data['history'] = fetch_appointment_data($data['appointment_id']);
            if (isset($data['notes'])) {
                $data['notes'] = htmlentities($data['notes']);
            }
            $this->load->view('modals/update_internal_crm', $data);
        }
    }

    /**
     * Modal edit and modal update trigger views with data
     *
     * @return void
     */
    public function modal_leads_contacts_crm()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $data['staff'] = $this->staff_model->get('', ['active' => 1]);

        $user_id = $this->input->post('user_id');
        $type = $this->input->post('type');

        if ($data['staff']) {
            if ($user_id && $type == 'lead') {
                $this->load->model('leads_model');
                $data['user'] = $this->leads_model->get($user_id);
                $data['user']->type = _l('lead');
            } else {
                $this->load->model('clients_model');
                $data['user'] = $this->clients_model->get_contact($user_id);
                $data['user']->type = _l('contact');
            }
        }

        $this->load->view('modals/create_leads_contacts_crm', $data);
    }

    /**
     * Update appointment
     *
     * @return void
     */
    public function update()
    {

        $appointment = $this->input->post();
        $appointment['notes'] = $this->input->post('notes', false);

        if (staff_can('edit', 'appointments') || staff_appointments_responsible()) {
            if ($appointment) {
                if ($this->apm->update_appointment($appointment)) {
                    header('Content-Type: application/json');
                    echo json_encode(['result' => true]);
                }
            }
        }
    }


    /**
     * Update appointment
     *
     * @return void
     */
    public function update_internal_crm()
    {

        $appointment = $this->input->post();
        $appointment['notes'] = $this->input->post('notes', false);

        if (staff_can('edit', 'appointments') || staff_appointments_responsible()) {
            if ($appointment) {
                if ($this->apm->update_internal_crm_appointment($appointment)) {
                    header('Content-Type: application/json');
                    echo json_encode(['result' => true]);
                }
            }
        }
    }

    /**
     * Create appointment
     *
     * @return void
     */
    public function create()
    {
        if (!staff_can('create', 'appointments') && !staff_appointments_responsible()) {
            access_denied();
        }

        $data = $this->input->post();

        if (!empty($data)) {
            if ($this->apm->insert_appointment($data)) {
                if (!isset($data['rel_id'])) {
                    $sql = "SELECT * FROM " . db_prefix() . "leads WHERE email LIKE '" . $data['email'] . "'";
                    $query  = $this->db->query($sql);
                    $lead =  $query->row_array();
                    if (isset($lead['id'])) {
                        $data['rel_id'] = $lead['id'];
                    } else {
                        // create lead
                        $lead_data = [];
                        $lead_data['email'] = $data['email'];
                        $lead_data['name'] = $data['name'];
                        $lead_data['description'] = '';
                        $lead_data['address'] = '';
                        $lead_data['status'] = '2';
                        $lead_data['source'] = '1';
                        $lead_data['assigned'] = get_staff_user_id();
                        $this->leads_model->add($lead_data);
                        $sql = "SELECT * FROM " . db_prefix() . "leads WHERE email LIKE '" . $data['email'] . "'";
                        $query  = $this->db->query($sql);
                        $lead =  $query->row_array();
                        $data['rel_id'] = $lead['id'];
                    }
                }
                if (isset($data['rel_id'])) {
                    $log = [
                        'date'            => date('Y-m-d H:i:s'),
                        'description'     => _l('lead_appointment_create'),
                        'leadid'          => $data['rel_id'],
                        'staffid'         => get_staff_user_id(),
                        'full_name'       => get_staff_full_name($data['rel_id']),
                    ];

                    $this->db->insert(db_prefix() . 'lead_activity_log', $log);
                }

                header('Content-Type: application/json');
                echo json_encode(['result' => true]);
            }
        }
    }


    /**
     * Create internal appointment for staff
     *
     * @return void
     */
    public function create_internal_crm()
    {
        if (!staff_can('create', 'appointments') && !staff_appointments_responsible()) {
            access_denied();
        }

        $data = $this->input->post();

        if (!empty($data)) {
            if ($this->apm->insert_internal_crm_appointment($data)) {
                header('Content-Type: application/json');
                echo json_encode(['result' => true]);
            }
        }
    }

    /**
     * Delete appointment
     *
     * @param [type] appointment $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $appointment = $this->apm->get_appointment_data($id);

        if (staff_can('delete', 'appointments') && $appointment['created_by'] == get_staff_user_id() || staff_appointments_responsible()) {
            if (!$this->input->is_ajax_request() && isset($id)) {
                if ($this->apm->delete_appointment($id)) {
                    appointly_redirect_after_event('success', _l('appointment_deleted'));
                }
            }

            if (isset($id)) {
                if ($this->apm->delete_appointment($id)) {
                    echo json_encode(['success' => true, 'message' => _l('appointment_deleted')]);
                    return;
                }
            } else {
                show_404();
            }
        }
    }


    /**
     * Approve new appointment
     *
     * @return void
     */
    public function approve()
    {
        if (!is_admin() && !staff_appointments_responsible()) {
            access_denied();
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode(['result' => $this->apm->approve_appointment($this->input->post('appointment_id'))]);
            die;
        }

        if ($this->apm->approve_appointment($this->input->get('appointment_id'))) {
            appointly_redirect_after_event('success', _l('appointment_appointment_approved'));
        }
    }

    /**
     * Mark appointment as finished
     *
     * @return bool
     */
    public function finished()
    {
        $id = $this->input->post('id');

        $appointment = $this->apm->get_appointment_data($id);

        if (staff_can('edit', 'appointments') && $appointment['created_by'] == get_staff_user_id() || staff_appointments_responsible()) {
            return $this->apm->mark_as_finished($id);
        }

        return false;
    }

    /**
     * Mark appointment as ongoing
     *
     * @return void|boolean
     */
    public function mark_as_ongoing_appointment()
    {
        $id = $this->input->post('id');

        $appointment = $this->apm->get_appointment_data($id);

        if (staff_can('edit', 'appointments') && $appointment['created_by'] == get_staff_user_id() || staff_appointments_responsible()) {
            return $this->apm->mark_as_ongoing($appointment);
        }

        return false;
    }

    /**
     * Mark appointment as cancelled
     *
     * @return void|boolean
     */
    public function cancel_appointment()
    {
        $id = $this->input->post('id');

        $appointment = $this->apm->get_appointment_data($id);

        if (staff_can('edit', 'appointments') && $appointment['created_by'] == get_staff_user_id() || staff_appointments_responsible()) {
            return $this->apm->cancel_appointment($id);
        }

        return false;
    }

    /**
     * Approve new appointment
     *
     * @return void
     */
    public function status()
    {
        if (!is_admin() && !staff_appointments_responsible()) {
            access_denied();
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode(['result' => $this->apm->appointment_status($this->input->post('appointment_id'), $this->input->post('status_id'))]);
            die;
        }

        if ($this->apm->appointment_status($this->input->get('appointment_id'), $this->input->post('status_id'))) {
            appointly_redirect_after_event('success', _l('appointment_appointment_approved'));
        }
    }

    /**
     * Get today's appointments
     *
     * @return array
     */
    public function getTodaysAppointments()
    {
        return $this->apm->fetch_todays_appointments();
    }

    /**
     * Send appointment early reminders
     *
     * @return void
     */
    public function send_appointment_early_reminders()
    {
        if ($this->staff_no_view_permissions || !staff_appointments_responsible()) {
            access_denied();
        }

        if ($this->apm->send_appointment_early_reminders($this->input->post('id'))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


    /**
     * Load user settings view
     *
     * @return void Returns view
     */
    public function user_settings_view()
    {
        if ($this->staff_no_view_permissions) {
            access_denied();
        }

        $data = getAppointlyUserMeta();

        $data['filters'] = get_appointments_table_filters();

        $this->load->view('users/index', $data);
    }


    /**
     * User settings request for updating options in meta table
     *
     * @return void
     */
    public function user_settings()
    {
        $data = $this->input->post();

        if ($data) {

            $meta = [
                'appointly_show_summary'         => $this->input->post('appointly_show_summary'),
                'appointly_default_table_filter' => $this->input->post('appointly_default_table_filter'),
            ];

            $this->apm->update_appointment_types($data, $meta);

            appointly_redirect_after_event('success', _l('settings_updated'), 'appointments/user_settings_view/settings');
        }
    }

    /**
     * Add new appointment type
     *
     * @return bool
     */
    public function new_appointment_type()
    {
        if (!staff_appointments_responsible() && !staff_can('create', 'appointments')) {
            access_denied();
        }

        if ($this->input->post()) {
            if ($this->apm->new_appointment_type(
                $this->input->post('type'),
                $this->input->post('color')
            )) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            }
        }
        return false;
    }


    /**
     * Delete appointment type
     *
     * @param [string] id
     *
     * @return boolean
     */
    public function delete_appointment_type()
    {
        if (!staff_can('delete', 'appointments') && !staff_appointments_responsible()) {
            access_denied();
        }
        return $this->apm->delete_appointment_type($this->input->post('id'));
    }

    /**
     * Add event to google calendar
     *
     * @return void
     */
    public function addEventToGoogleCalendar()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        if (!staff_can('edit', 'appointments') && !staff_appointments_responsible()) {
            access_denied();
        }

        $data = [];

        $data = $this->input->post();
        if ($data && !empty($data)) {
            header('Content-Type: application/json');
            $result = $this->apm->add_event_to_google_calendar($data);
            if ($result) {
                echo json_encode($result);
            }
        }
    }

    /**
     * Request new appointment feedback
     *
     * @param string $id
     *
     * @return void
     */
    public function requestAppointmentFeedback($id)
    {
        if ($id && !empty($id)) {
            header('Content-Type: application/json');
            $result = $this->apm->request_appointment_feedback($id);
            if ($result) {
                echo json_encode($result);
            }
        }
    }

    /**
     * Get attendee details
     *
     * @return void
     */
    public function getAttendeeData()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        if ($this->input->post('ids')) {
            header('Content-Type: application/json');
            echo json_encode($this->atm->details($this->input->post('ids')));
        }
    }

    /**
     * Add new outlook event to calendar
     *
     * @return void
     */
    public function newOutlookEvent()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $data = [];

        $data = $this->input->post();

        if ($data && !empty($data)) {
            header('Content-Type: application/json');
            echo json_encode(['result' => $this->apm->insertNewOutlookEvent($data)]);
        }
    }

    /**
     * Add new outlook event to calendar from existing appointment
     *
     * @return void
     */
    public function updateAndAddExistingOutlookEvent()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $data = [];
        $data = $this->input->post();

        if ($data && !empty($data)) {
            header('Content-Type: application/json');
            echo json_encode(['result' => $this->apm->updateAndAddExistingOutlookEvent($data)]);
        }
    }

    /**
     * Send custom email to request meet via Google Meet
     *
     * @return void
     */
    public function sendCustomEmail()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $data = [];

        $data = $this->input->post();

        if ($data && !empty($data)) {
            header('Content-Type: application/json');
            echo json_encode($this->apm->sendGoogleMeetRequestEmail($data));
        }
    }

    /**
     * Edit appointment from view directly
     */
    public function edit_from_view()
    {
        if ($this->staff_no_view_permissions) {
            access_denied('Appointments');
        }

        $from_view_id = $this->input->get('from_view_id');

        if ($from_view_id) {
            $this->session->set_userdata(['from_view_id' => $from_view_id]);
            echo json_encode(['success' => true]);
            return;
        }

        echo json_encode(['success' => false]);
    }
}
