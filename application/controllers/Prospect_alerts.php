<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospect_alerts extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Prospect_alerts_model');
        $this->load->model('leadevo/Prospect_categories_model'); // Assuming you need this model for categories
    }

    public function index()
    {
        $search = $this->input->get('search');
        $data['categories'] = $this->Prospect_categories_model->get_all();
        $filter = $this->input->get('filter');
        $data['alerts'] = $this->Prospect_alerts_model->get_all();
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
            'is_active' => $this->input->post('is_active') ? 1 : 0,
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
    public function view_details($id)
    {
        $data['alert'] = $this->Prospect_alerts_model->get($id);
        $this->data($data);
        $this->view('clients/prospect_alerts/view');
        $this->layout();
    }
}
