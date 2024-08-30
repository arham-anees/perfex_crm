<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospect_alerts extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Prospect_alerts_model');
        $this->load->model('leadevo/Prospect_categories_model'); // Assuming you need this model for categories
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
    }

    public function index()
    {
        log_message('error', 'step1');
        $search = $this->input->get('search');
        $data['categories'] = $this->Prospect_categories_model->get_all();
        log_message('error', 'step2');
        $filter = $this->input->get('filter');
        $data['alerts'] = $this->Prospect_alerts_model->get_all();
        log_message('error', 'step3');
        $this->data($data);
        $this->view('clients/prospect_alerts/prospect_alerts');
        $this->layout();
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'prospect_category_id' => $this->input->post('prospect_category_id'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'status' => 0,
                'is_exclusive' => (int) $this->input->post('is_exclusive'),
            ];

            // Inserting the data into tblleadevo_prospect_alerts
            $this->Prospect_alerts_model->insert($data);
            redirect('prospect_alerts');
        } else {
            // Fetch categories from tblleadevo_prospect_categories for the dropdown
            $data['prospect_categories'] = $this->Prospect_categories_model->get_all();

            // Passing categories to the view
            $this->data($data);
            $this->view('clients/prospect_alerts/create');
            $this->layout();
        }
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            // Fetch and prepare the updated data
            $data = [
                'name' => $this->input->post('name'),
                'prospect_category_id' => $this->input->post('prospect_category_id'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'is_exclusive' => (int) $this->input->post('is_exclusive')
            ];

            // Update the prospect alert in the database
            $this->Prospect_alerts_model->update($id, $data);
            redirect('prospect_alerts');
        } else {
            // Fetch the current prospect alert data
            $data['alert'] = $this->Prospect_alerts_model->get($id);
            // Fetch all categories for the dropdown
            $data['prospect_categories'] = $this->Prospect_categories_model->get_all();

            // Load the edit view with the current data
            $this->data($data);
            $this->view('clients/prospect_alerts/edit');
            $this->layout();
        }
    }

    public function delete($id)
    {
        if ($this->Prospect_alerts_model->delete($id)) {
            set_alert('success', 'Prospect alert deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete prospect alert.');
        }
        redirect('prospect_alerts');
    }
    public function activate($id)
    {
        if ($this->Prospect_alerts_model->activate($id)) {
            set_alert('success', _l('leadevo_prospect_alert_activated'));
        } else {
            set_alert('danger', _l('leadevo_prospect_alert_activation_failed'));
        }
        redirect('prospect_alerts');
    }
    public function deactivate($id)
    {
        if ($this->Prospect_alerts_model->deactivate($id)) {
            set_alert('success', _l('leadevo_prospect_alert_deactivated'));
        } else {
            set_alert('danger', _l('leadevo_prospect_alert_deactivation_failed'));
        }
        redirect('prospect_alerts');
    }
    public function details($id)
    {
        $data['alert'] = $this->Prospect_alerts_model->get($id);
        $this->data($data);
        $this->view('clients/prospect_alerts/view');
        $this->layout();
    }

    public function send_alerts()
    {
        $this->Prospect_alerts_model->send_alerts();
    }
}
