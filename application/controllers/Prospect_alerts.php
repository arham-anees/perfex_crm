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
        if (is_client_logged_in() && !is_contact_email_verified()) {
            redirect(site_url('verification'));
        }
        if(!is_onboarding_completed()){
            redirect(site_url('onboarding'));
        }
    }

    public function index()
    {
        $search = $this->input->get('search');
        $data['categories'] = $this->Prospect_categories_model->get_all();
        $filter = $this->input->get('filter');
        $data['alerts'] = $this->Prospect_alerts_model->get_all_client();
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
                'acquisition_channel_id' => $this->input->post('acquisition_channel_id'),
                'industry_id' => $this->input->post('industry_id'),
                'source_id' => $this->input->post('source_id'),
                'verified_whatsapp' => $this->input->post('verified_whatsapp') == null ? null : (int) $this->input->post('verified_whatsapp'),
                'verified_sms' => $this->input->post('verified_sms') == null ? null : (int) $this->input->post('verified_sms'),
                'verified_staff' => $this->input->post('verified_staff') == null ? null : (int) $this->input->post('verified_staff'),
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
                'is_exclusive' => (int) $this->input->post('is_exclusive'),
                'acquisition_channel_id' => $this->input->post('acquisition_channel_id'),
                'industry_id' => $this->input->post('industry_id'),
                'source_id' => $this->input->post('source_id'),
                'verified_whatsapp' => $this->input->post('verified_whatsapp') == null ? null : (int) $this->input->post('verified_whatsapp'),
                'verified_sms' => $this->input->post('verified_sms') == null ? null : (int) $this->input->post('verified_sms'),
                'verified_staff' => $this->input->post('verified_staff') == null ? null : (int) $this->input->post('verified_staff')
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
