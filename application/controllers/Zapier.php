<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Zapier extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Prospect_status_model');
        $this->load->model('leadevo/Prospect_types_model');
        $this->load->model('leadevo/Prospect_categories_model');
        $this->load->model('leadevo/Acquisition_channels_model');
        $this->load->model('leadevo/Industries_model');
        $this->load->model('Leads_model');
        $this->load->model('Misc_model');
        $this->load->model('leadevo/Reported_Prospects_model');
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
        if (is_client_logged_in() && !is_contact_email_verified()) {
            redirect(site_url('verification'));
        }
    }

    public function fetch()
    {
        $data = $this->Misc_model->get_zapier_config(get_client_user_id());
        echo json_encode(['status' => 'success', 'data' => json_encode($data)]);
    }
    public function create()
    {
        if ($this->input->method() === 'post') {
            $webhook = $this->input->post('webhook');
            if (!$webhook) {
                echo json_encode(['status' => 'error', 'message' => 'webhook is required']);
                return;
            }
            $data = $this->input->post();
            $config = $this->Misc_model->set_zapier_config(get_client_user_id(), $data);
            redirect(site_url('clients/zapier/'));
        } else {
            $this->view('clients/zapier/create');
            $this->layout();
        }
    }
    public function edit($id)
    {
        if ($this->input->method() === 'post') {
            $webhook = $this->input->post('webhook');
            if (!$webhook) {
                echo json_encode(['status' => 'error', 'message' => 'webhook is required']);
                return;
            }
            $data = $this->input->post();
            $config = $this->Misc_model->update_zapier_config($id, get_client_user_id(), $data);
            redirect(site_url('clients/zapier'));
        } else {
            $data['webhook'] = $this->Misc_model->get_zapier_config_id($id);
            $this->data($data);
            $this->view('clients/zapier/edit');
            $this->layout();
        }
    }
    public function delete($id)
    {

        $data = ['is_active' => 0];
        $config = $this->Misc_model->update_zapier_config($id, get_client_user_id(), $data);
        redirect(site_url('clients/zapier'));
    }
}
