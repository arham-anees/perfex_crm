<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
        //load some models
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Prospects_model');
    }

    public function index()
    {
        $data['campaigns'] = $this->Campaigns_model->get_all();
        $data['prospects'] = $this->Prospects_model->get_all();
        $this->load->view('dashboard', $data);
    }


    public function receive_prospect()
    {
        $this->load->view('clients/dashboard/receive_prospect');
    }

}