<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        //load some models
        $this->load->model('Campaigns_model');
        $this->load->model('Prospects_model');
    }

    public function index()
    {
        $data['campaigns'] = $this->Campaigns_model->get_all();
        $data['prospects'] = $this->Prospects_model->get_all();
        $this->load->view('client/dashboard/dashboard', $data);
    }
        
}