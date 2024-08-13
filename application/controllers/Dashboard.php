<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        //load some models
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Cart_model');
    }

    public function index()
    {
        $data['campaigns'] = $this->Campaigns_model->get_all();
        $data['prospects'] = $this->Prospects_model->get_all();
        $this->load->view('dashboard', $data);
    }


    public function receive_prospect()
    {
        echo json_encode(['status' => 'success']);
    }
    public function add_to_cart()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $this->Cart_model->add_item($data);
            echo json_encode(array('status' => 'success', 'message' => 'Prospect added to cart'));
        }
        redirect(site_url('marketplace'));
    }

}