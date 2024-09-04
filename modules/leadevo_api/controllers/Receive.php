<?php defined('BASEPATH') or exit('No direct script access allowed');

class Receive extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Leads_model');
    }

    public function index()
    {
        try {
            
            $lead_str = $this->input->post('lead');
            if (!isset($lead_str)) {
                throw new Exception('Lead data is missing in recieving end');
            }
            $lead_str = base64_decode($lead_str);
            if (isset($lead['id']))
                unset($lead['id']);
            $lead = json_decode($lead_str, true);
            
            if(!isset($lead['description']))$lead['description'] = '';
            if(!isset($lead['address']))$lead['address'] = '';
            $lead['source'] = getZapierSourceId()['id'];
            $lead['status'] = 2;//contact
            $lead['hash'] = app_generate_hash();

            $this->Leads_model->add_received($lead);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}