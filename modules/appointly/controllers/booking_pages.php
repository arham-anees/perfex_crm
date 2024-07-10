<?php defined('BASEPATH') or exit('No direct script access allowed');

class Booking_pages extends AdminController
{
    private $staff_no_view_permissions;

    public function __construct()
    {
        parent::__construct();

        $this->staff_no_view_permissions = !staff_can('view', 'appointments') && !staff_can('view_own', 'appointments');

        $this->load->model('appointly_model', 'apm');
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

         $data['booking_pages'] = $this->booking_page_model->get_all();

        $this->load->view('booking_pages/booking_pages', $data);
    }

    public function booking_page($link=''){
        if ($link=='') {
            redirect(admin_url('appointly/booking_pages'));
        }
        $data['booking_page'] = $this->booking_page_model->get_by_url( $link);
        $data['link'] = $link;
        $this->load->view('booking_pages/booking_page_details', $data);
    }

    public function create() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Read data from POST
            $data['name'] = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['url'] = $this->input->post('url');
            $data['duration_minutes'] = $this->input->post('duration_minutes');
            $data['appointly_responsible_person'] = $this->input->post('appointly_responsible_person');
            $data['callbacks_responsible_person'] = $this->input->post('callbacks_responsible_person');
            $data['appointly_available_hours'] = $this->input->post('appointly_available_hours');
            $data['appointly_default_feedbacks'] = $this->input->post('appointly_default_feedbacks');
            $data['google_api_key'] = $this->input->post('google_api_key');
            $data['appointly_google_client_secret'] = $this->input->post('appointly_google_client_secret');
            $data['appointly_outlook_client_id'] = $this->input->post('appointly_outlook_client_id');
            $data['appointly_appointments_recaptcha'] = $this->input->post('appointly_appointments_recaptcha');
            $data['appointly_busy_times_enabled'] = $this->input->post('appointly_busy_times_enabled');
            $data['appointly_also_delete_in_google_calendar'] = $this->input->post('appointly_also_delete_in_google_calendar');
            $data['appointments_disable_weekends'] = $this->input->post('appointments_disable_weekends');
            $data['appointly_view_all_in_calendar'] = $this->input->post('appointly_view_all_in_calendar');
            $data['appointly_client_meeting_approved_default'] = $this->input->post('appointly_client_meeting_approved_default');
            $data['appointly_tab_on_clients_page'] = $this->input->post('appointly_tab_on_clients_page');
            $data['appointly_show_clients_schedule_button'] = $this->input->post('appointly_show_clients_schedule_button');
            $data['appointments_show_past_times'] = $this->input->post('appointments_show_past_times');
            $data['callbacks_mode_enabled'] = $this->input->post('callbacks_mode_enabled');
        } else {
            // Set default values for GET request
            $data['name'] = '';
            $data['description'] = '';
            $data['url'] = '';
            $data['duration_minutes'] = 30;
            $data['appointly_responsible_person'] = [];
            $data['callbacks_responsible_person'] = [];
            $data['appointly_available_hours'] = '';
            $data['appointly_default_feedbacks'] = '';
            $data['google_api_key'] = '';
            $data['appointly_google_client_secret'] = '';
            $data['appointly_outlook_client_id'] = '';
            $data['appointly_appointments_recaptcha'] = false;
            $data['appointly_busy_times_enabled'] = true;
            $data['appointly_also_delete_in_google_calendar'] = true;
            $data['appointments_disable_weekends'] = true;
            $data['appointly_view_all_in_calendar'] = false;
            $data['appointly_client_meeting_approved_default'] = true;
            $data['appointly_tab_on_clients_page'] = false;
            $data['appointly_show_clients_schedule_button'] = false;
            $data['appointments_show_past_times'] = false;
            $data['callbacks_mode_enabled'] = false;
        }
    
        // Load the view with the data
        $this->load->view('booking_pages/create', $data);
    }
    


}
