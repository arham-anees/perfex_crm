<?php defined('BASEPATH') or exit('No direct script access allowed');

class Booking_pages extends AdminController
{
    private $staff_no_view_permissions;

    public function __construct()
    {
        parent::__construct();

        $this->staff_no_view_permissions = !staff_can('view', 'appointments') && !staff_can('view_own', 'appointments');

        $this->load->model('appointly_model', 'apm');
        $this->load->model('leads_model');
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

        // $data['td_appointments'] = $this->getTodaysAppointments();

        // $this->load->view('index', $data);
        $this->load->view('booking_pages');
    }

    public function booking_page($id=0){
        if ($id==0) {
            redirect(admin_url('appointly/booking_pages'));
        }
        $this->load->view('booking_page_details');
    }


}
