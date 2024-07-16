<?php defined('BASEPATH') or exit('No direct script access allowed');

class Booking_pages extends AdminController
{
    private $staff_no_view_permissions;

    public function __construct()
    {
        parent::__construct();

        $this->staff_no_view_permissions = !staff_can('view', 'appointments') && !staff_can('view_own', 'appointments');

        $this->load->model('booking_page_model');
    }

    /**
     * Main view
     *
     * @return void
     */
    public function index()
    {
        if ($this->staff_no_view_permissions) {
            access_denied('booking_pages');
        }

         $data['booking_pages'] = $this->booking_page_model->get_all();

        $this->load->view('booking_pages/booking_pages', $data);
    }

    public function booking_page($id=''){
        if ($id=='') {
            redirect(admin_url('appointly/booking_pages'));
        }
        $data['booking_page'] = $this->booking_page_model->get( $id);
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);

        $this->load->view('booking_pages/booking_page_details', $data);
    }
    function arrayToStringWithQuotes($array) {
        // Convert each integer to a string and wrap it in single quotes
        $stringArray = array_map(function($value) {
            return '"' . (string)$value . '"';
        }, $array);
    
        // Convert the array to a string with comma as the delimiter
        return '[' . implode(',', $stringArray) . ']';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $data['appointly_appointments_recaptcha'] = (bool)$this->input->post('appointly_appointments_recaptcha');
            $data['appointly_busy_times_enabled'] = (bool)$this->input->post('appointly_busy_times_enabled');
            $data['appointly_also_delete_in_google_calendar'] = (bool)$this->input->post('appointly_also_delete_in_google_calendar');
            $data['appointments_disable_weekends'] = (bool)$this->input->post('appointments_disable_weekends');
            $data['appointly_view_all_in_calendar'] = (bool)$this->input->post('appointly_view_all_in_calendar');
            $data['appointly_client_meeting_approved_default'] = (bool)$this->input->post('appointly_client_meeting_approved_default');
            $data['appointly_tab_on_clients_page'] = (bool)$this->input->post('appointly_tab_on_clients_page');
            $data['appointly_show_clients_schedule_button'] = (bool)$this->input->post('appointly_show_clients_schedule_button');
            $data['appointments_show_past_times'] = (bool)$this->input->post('appointments_show_past_times');
            $data['callbacks_mode_enabled'] = (bool)$this->input->post('callbacks_mode_enabled');
            $data['google_client_id'] = $this->input->post('google_client_id');
            $data['simultaneous_appointments'] = (int)$this->input->post('simultaneous_appointments');
            $data['is_active'] = (bool)1;
            if(is_array($data['appointly_available_hours'])){
                $data['appointly_available_hours'] = $this->arrayToStringWithQuotes($data['appointly_available_hours']);// '[' .implode(',',$data['appointly_available_hours']).']';
            }
            if(is_array($data['appointly_default_feedbacks'])){
            $data['appointly_default_feedbacks'] = $this->arrayToStringWithQuotes($data['appointly_default_feedbacks']);//'['.implode(',',$data['appointly_default_feedbacks']).']';
            }
            if($data['name']=='' || $data['url']==''){
              $data['error_message']='Name and URL are required';
            }
            else{
               $existing = $this->booking_page_model->get_by_url($data['url']);
               if(isset($existing) && count($existing)>0){
                   //redirect(admin_url('appointly/booking_pages/create'));
              $data['error_message']='URL already in use. Please use another url.';
               }
               else{
                unset( $data['error_message']);
                unset( $data['google_client_id']);
               $this->booking_page_model->create($data);
               echo json_encode(['success' => true, 'message' => _l('booking_page_created')]);
               return;
               }
           }
        }
        else{
            $data['error_message']='';
            // Set default values for GET request
            $data['name'] = '';
            $data['description'] = '';
            $data['url'] = '';
            $data['duration_minutes'] = 30;
            $data['appointly_responsible_person'] =get_option('appointly_responsible_person');
            $data['callbacks_responsible_person'] =get_option('callbacks_responsible_person');
            $data['appointly_available_hours'] =get_option('appointly_available_hours');
            $data['appointly_default_feedbacks'] =get_option('appointly_default_feedbacks');
            $data['google_api_key'] =get_option('google_api_key');
            $data['google_client_id'] = get_option('google_client_id');
            $data['appointly_google_client_secret'] =get_option('appointly_google_client_secret');
            $data['appointly_outlook_client_id'] =get_option('appointly_outlook_client_id');
            $data['appointly_appointments_recaptcha'] =get_option('appointly_appointments_recaptcha');
            $data['appointly_busy_times_enabled'] =get_option('appointly_busy_times_enabled');
            $data['appointly_also_delete_in_google_calendar'] =get_option('appointly_also_delete_in_google_calendar');
            $data['appointments_disable_weekends'] =get_option('appointments_disable_weekends');
            $data['appointly_view_all_in_calendar'] =get_option('appointly_view_all_in_calendar');
            $data['appointly_client_meeting_approved_default'] =get_option('appointly_client_meeting_approved_default');
            $data['appointly_tab_on_clients_page'] =get_option('appointly_tab_on_clients_page');
            $data['appointly_show_clients_schedule_button'] =get_option('appointly_show_clients_schedule_button');
            $data['appointments_show_past_times'] =get_option('appointments_show_past_times');
            $data['callbacks_mode_enabled'] =get_option('callbacks_mode_enabled');
            $data['simultaneous_appointments'] = 1;
            $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        }
        if(!isset($data['error_message'])){
            $data['error_message']='';
        }
        // Load the view with the data
        $this->load->view('booking_pages/create', $data);
    }

    public function delete($id='') {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
        // Validate the input data
        if (empty($id)) {
            // Redirect with an error if validation fails
            echo json_encode(['success' => false, 'message' => _l('booking_invalid_id') . $id]);
        }

        // Process the data (e.g., delete from the database)
        $deleted = $this->booking_page_model->delete($id);

        // Redirect with a success or error message
        if ($deleted) {
            echo json_encode(['success' => true, 'message' => _l('booking_successful_delete')]);
        } else {
            echo json_encode(['success' => false, 'message' => _l('booking_failed_delete')]);
        }
    } else {
            echo json_encode(['success' => true, 'message' => _l('appointment_deleted') . $id]);
    }
    }
    public function update($id='') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $data['appointly_appointments_recaptcha'] = (bool)$this->input->post('appointly_appointments_recaptcha');
            $data['appointly_busy_times_enabled'] = (bool)$this->input->post('appointly_busy_times_enabled');
            $data['appointly_also_delete_in_google_calendar'] = (bool)$this->input->post('appointly_also_delete_in_google_calendar');
            $data['appointments_disable_weekends'] = (bool)$this->input->post('appointments_disable_weekends');
            $data['appointly_view_all_in_calendar'] = (bool)$this->input->post('appointly_view_all_in_calendar');
            $data['appointly_client_meeting_approved_default'] = (bool)$this->input->post('appointly_client_meeting_approved_default');
            $data['appointly_tab_on_clients_page'] = (bool)$this->input->post('appointly_tab_on_clients_page');
            $data['appointly_show_clients_schedule_button'] = (bool)$this->input->post('appointly_show_clients_schedule_button');
            $data['appointments_show_past_times'] = (bool)$this->input->post('appointments_show_past_times');
            $data['callbacks_mode_enabled'] = (bool)$this->input->post('callbacks_mode_enabled');
            $data['simultaneous_appointments'] = (int)$this->input->post('simultaneous_appointments');
            $data['google_client_id'] = $this->input->post('google_client_id');
            if(is_array($data['appointly_available_hours'])){
                $data['appointly_available_hours'] = $this->arrayToStringWithQuotes($data['appointly_available_hours']);// '[' .implode(',',$data['appointly_available_hours']).']';
            }
            if(is_array($data['appointly_default_feedbacks'])){
            $data['appointly_default_feedbacks'] = $this->arrayToStringWithQuotes($data['appointly_default_feedbacks']);//'['.implode(',',$data['appointly_default_feedbacks']).']';
            }
            if($data['name']=='' || $data['url']==''){
              $data['error_message']='Name and URL are required';
            }
            else{
               $existing = $this->booking_page_model->get_by_url($data['url']);
               if(isset($existing) && count($existing)>0 && $existing['id'] != $id){
                   //redirect(admin_url('appointly/booking_pages/create'));
              $data['error_message']='URL already in use. Please use another url.';
               }
               else{
                unset( $data['error_message']);
                unset( $data['google_client_id']);
               $this->booking_page_model->update($data, $id);
               redirect(admin_url('appointly/booking_pages'));
               }
           }
        }
        else{
            if(!isset($data['error_message'])){
                $data['error_message']='';
                }
        }
            $booking_page = $this->booking_page_model->get($id);
            $data['id']=$id;
            // Set default values for GET request
            $data['name'] = $booking_page['name'];
            $data['description'] = $booking_page['description'];
            $data['url'] = $booking_page['url'];
            $data['duration_minutes'] = $booking_page['duration_minutes'];
            $data['appointly_responsible_person'] = $booking_page['appointly_responsible_person'];
            $data['callbacks_responsible_person'] = $booking_page['callbacks_responsible_person'];
            $data['appointly_available_hours'] = $booking_page['appointly_available_hours'];
            $data['appointly_default_feedbacks'] = $booking_page['appointly_default_feedbacks'];
            $data['google_api_key'] = $booking_page['google_api_key'];
            // $data['google_client_id'] =  $booking_page['google_client_id'];
            $data['appointly_google_client_secret'] = $booking_page['appointly_google_client_secret'];
            $data['appointly_outlook_client_id'] = $booking_page['appointly_outlook_client_id'];
            $data['appointly_appointments_recaptcha'] = $booking_page['appointly_appointments_recaptcha'];
            $data['appointly_busy_times_enabled'] = $booking_page['appointly_busy_times_enabled'];
            $data['appointly_also_delete_in_google_calendar'] = $booking_page['appointly_also_delete_in_google_calendar'];
            $data['appointments_disable_weekends'] = $booking_page['appointments_disable_weekends'];
            $data['appointly_view_all_in_calendar'] = $booking_page['appointly_view_all_in_calendar'];
            $data['appointly_client_meeting_approved_default'] = $booking_page['appointly_client_meeting_approved_default'];
            $data['appointly_tab_on_clients_page'] = $booking_page['appointly_tab_on_clients_page'];
            $data['appointly_show_clients_schedule_button'] = $booking_page['appointly_show_clients_schedule_button'];
            $data['appointments_show_past_times'] = $booking_page['appointments_show_past_times'];
            $data['callbacks_mode_enabled'] = $booking_page['callbacks_mode_enabled'];
            $data['simultaneous_appointments'] = $booking_page['simultaneous_appointments'];
            $data['google_client_id']='';
            $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        // }
   
        // Load the view with the data
        $this->load->view('booking_pages/create', $data);
    }

    public function generate_options(){

        // Fetch minutes_duration from the GET request
        $minutesDuration = isset($_GET['minutes_duration']) ? intval($_GET['minutes_duration']) : 15; // Default to 15 minutes if not provided

        // Logic to generate $appointmentHours and $savedHours based on $minutesDuration
        // Example:
        $appointmentHours = array();
        $savedHours = array();

        // Generate options based on $minutesDuration (replace with your actual logic)
        for ($minutes = 0; $minutes < 24 *60; $minutes+=$minutesDuration) {
            $time = sprintf('%02d', ($minutes/60)) . ':'. sprintf('%02d', ($minutes%60));
            $appointmentHours[] = array(
                'value' => $time,
                'name' => $time
            );
        }

        // HTML generation
        $html = '<div class="form-group hours">';
        $html .= '<label for="appointment_hours">' . _l('appointments_default_hours_label') . '</label>';
        $html .= '<select class="selectpicker" name="appointly_available_hours[]" id="appointment_hours" data-width="100%" multiple="true">';

        foreach ($appointmentHours as $hour) {
            $html .= '<option value="' . htmlspecialchars($hour['value']) . '"';
            if ($savedHours !== null && in_array($hour['value'], $savedHours)) {
                $html .= ' selected';
            }
            $html .= '>' . htmlspecialchars($hour['name']) . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';

        // Output the generated HTML
        echo $html;
    }

}
