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
        $this->load->model('Leads_model');
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
    }

    public function index()
    {
        $data['campaigns'] = $this->Campaigns_model->get_all();
        $data['prospects'] = $this->Prospects_model->get_all();
        $this->load->view('dashboard', $data);
    }


    public function receive_prospect()
    {
        $lead_str = $this->input->post('lead');
        $lead_str = base64_decode($lead_str);
        if (isset($lead['id']))
            unset($lead['id']);
        $lead = json_decode($lead_str, true);
        $lead['description'] = '';
        $lead['address'] = '';
        $lead['hash'] = app_generate_hash();

        $this->Leads_model->add_received($lead);
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