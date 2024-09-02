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
        // Check if the request method is POST
        if ($this->input->method() === 'post') {
            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $webhook = $this->input->post('webhook');
    
            // Validate the presence of necessary fields
            if (!$webhook) {
                echo json_encode(['status' => 'error', 'message' => 'Webhook is required']);
                return;
            }
    
            if (!$name || !$description) {
                echo json_encode(['status' => 'error', 'message' => 'Name and Description are required']);
                return;
            }
    
            // Prepare data to be inserted into the database
            $data = [
                'name' => $name,
                'description' => $description,
                'webhook' => $webhook  // Include the webhook in the insert data
            ];
    
            // Optional: If you have additional model operations for zapier config
            $config = $this->Misc_model->set_zapier_config(get_client_user_id(), $data);
    
            // Redirect after successful insertion
            redirect(site_url('clients/zapier/'));
        } else {
            // Load the form view if not a POST request
            $this->view('clients/zapier/create');
            $this->layout();
        }
    }
    
   public function edit($id)
{
    if ($this->input->method() === 'post') {
        // Retrieve POST data
        $webhook = $this->input->post('webhook');
        
        // Check if webhook is provided
        if (!$webhook) {
            echo json_encode(['status' => 'error', 'message' => 'Webhook is required']);
            return;
        }

        // Prepare data for update
        $data = [
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'webhook' => $this->input->post('webhook')
        ];

        $config = $this->Misc_model->update_zapier_config($id, get_client_user_id(), $data);
        
        redirect(site_url('clients/zapier'));
    } else {
        // Retrieve current webhook data
        $data['webhook'] = $this->Misc_model->get_zapier_config_id($id);
        
        // Set data and load view
        $this->data($data);
        $this->view('clients/zapier/edit');
        $this->layout();
    }
    public function fetch_webhook()
    {
        if ($this->input->method() !== 'get') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            return;
        }
    
        $id = $this->input->get('id');
    
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Webhook ID is required']);
            return;
        }
    
        $webhook = $this->Misc_model->get_webhook_by_id($id);
    
        if ($webhook) {
            echo json_encode(['status' => 'success', 'webhook_url' => $webhook->webhook]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Webhook not found']);
        }
    }
    

}

    public function delete($id)
    {
        $data = ['is_active' => 0];
       $this->Misc_model->update_zapier_config($id, get_client_user_id(), $data);
                redirect(site_url('clients/zapier'));
    }
  
    
}
