<?php defined('BASEPATH') or exit('No direct script access allowed');
class Marketplace extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Industries_model');

    }

    public function index()
    {
        $data['prospects'] = $this->Prospects_model->get_all();
        $data['industries'] = $this->Industries_model->get_all();
        $this->data($data);
        $this->view('clients/marketplace/leads');
        $this->layout();
    }
}
