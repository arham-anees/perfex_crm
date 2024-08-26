<?php defined('BASEPATH') or exit('No direct script access allowed');

class Receive extends AdminController
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
                throw new Exception('Lead data is missing');
            }
            $lead_str = base64_decode($lead_str);
            if (isset($lead['id']))
                unset($lead['id']);
            $lead = json_decode($lead_str, true);
            $lead['description'] = '';
            $lead['address'] = '';
            $lead['hash'] = app_generate_hash();

            $this->Leads_model->add_received($lead);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}