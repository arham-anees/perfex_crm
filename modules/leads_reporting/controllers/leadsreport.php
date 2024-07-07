<?php defined('BASEPATH') or exit('No direct script access allowed');

class LeadsReport extends AdminController
{
    private $staff_no_view_permissions;

    public function __construct()
    {
        parent::__construct();

        $this->staff_no_view_permissions = !staff_can('view', 'leads_report');

    }
    public function index()
    {
        // if ($this->staff_no_view_permissions) {
        //     access_denied('leads_report');
        // }

        $this->load->view('index');
    }

    public function report()
    {
        // if ($this->staff_no_view_permissions) {
        //     access_denied('leads_report');
        // }

        $this->load->view('index');
    }

}