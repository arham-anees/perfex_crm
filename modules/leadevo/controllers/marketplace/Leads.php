<?php defined('BASEPATH') or exit('No direct script access allowed');
class Leads extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Prospects_model');
        $this->load->model('Industries_model');
    }

    public function index()
    {
        $data['prospects'] = $this->Prospects_model->get_all();
        $data['industries'] = $this->Industries_model->get_all();
        $this->load->view('marketplace/leads', $data);
    }
}
