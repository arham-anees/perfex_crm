<?php defined('BASEPATH') or exit('No direct script access allowed');

class Custom_Scheduler extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($username = '')
    {
        if (empty($username)) {
            show_404();
        }

        // Check if the user exists in your system, if necessary
        // $this->db->where('username', $username);
        // $user = $this->db->get('tblstaff')->row();

        // if (!$user) {
        //     show_404();
        // }

        $data['username'] = $username;
        $this->load->view('schedule', $data);
    }
}
