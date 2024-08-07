<?php defined('BASEPATH') or exit('No direct script access allowed');

class ProspectAlert extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        //load some models
        $this->load->model('Prospect_alerts_model');
        $this->load->library('pagination');

    }

    public function index()
    {
        // Get the filter and search parameter from the URL
        $filter = $this->input->get('filter');
        $search = $this->input->get('search');

        // Fetch prospect alerts based on the filter and search parameters
        $data['prospect_alerts'] = $this->Prospect_alerts_model->get_filtered_prospect_alerts($search, $filter);

        // Pass the search term to the view
        $data['search'] = $search;

        // Load the view with the prospect alerts data
        $this->load->view('client/prospect_alerts/prospect_alerts', $data);
    }


    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'prospect_category_id' => $this->input->post('prospect_category_id'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
            ];

        // Ensure 'prospect_category_id' is not null and is being correctly set
        if (empty($data['prospect_category_id'])) {
            set_alert('danger', 'Prospect Category is required.');
            redirect('leadevo/client/prospectAlert/create');
        }

            $this->Prospect_alerts_model->insert($data);
            redirect('leadevo/client/prospectAlert');
        } else {
            $data['prospect_categories'] = $this->Prospect_alerts_model->get_prospect_categories(); // Added to fetch categories
            $this->load->view('client/prospect_alerts/prospect_alert_create', $data);

        }
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'prospect_category_id' => $this->input->post('prospect_category_id'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                // 'is_active' => $this->input->post('is_active'),
            ];


        // Ensure 'prospect_category_id' is not null and is being correctly set
        if (empty($data['prospect_category_id'])) {
            set_alert('danger', 'Prospect Category is required.');
            redirect('leadevo/client/prospectAlert/create');
        }

            if ($this->Prospect_alerts_model->update($id, $data)) {
                set_alert('success', 'Prospect_alert updated successfully.');
            } else {
                set_alert('danger', 'Failed to update Prospect_alert.');
            }
            redirect('leadevo/client/prospectAlert');
        } else {
            $data['prospect_alert'] = $this->Prospect_alerts_model->get($id);
            $data['prospect_categories'] = $this->Prospect_alerts_model->get_prospect_categories();
             $this->load->view('client/prospect_alerts/prospect_alert_view', $data);

        }
    }
    public function delete($id)
    {
        if ($this->Prospect_alerts_model->delete($id)) {
            set_alert('success', 'Prospect alert deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete Prospect alert.');
        }
        redirect('leadevo/client/prospectAlert'); // Updated redirection
    }

    public function toggleStatus($id)
    {
        // Get the current status of the prospect alert
        $prospect_alert = $this->Prospect_alerts_model->get($id);

        if ($prospect_alert) {
            // Toggle the status
            $new_status = $prospect_alert['status'] ? 0 : 1;

            // Update the status in the database
            $this->Prospect_alerts_model->update($id, ['is_active' => $new_status]);

            // Set the appropriate success message
            if ($new_status == 1) {
                set_alert('success', 'Prospect alert activated successfully.');
            } else {
                set_alert('success', 'Prospect alert deactivated successfully.');
            }
        } else {
            // Set an error message if the prospect alert is not found
            set_alert('danger', 'Prospect alert not found.');
        }

        // Redirect to the prospect alerts listing page
        redirect('leadevo/client/prospectAlert');
    }

}