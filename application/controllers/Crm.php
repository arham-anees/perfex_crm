<?php defined('BASEPATH') or exit('No direct script access allowed');

class Crm extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Misc_model');
        
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

    // Display the list of links
    public function index()
    {
        $data['links'] = $this->Misc_model->get_all_crm_links();

        $this->data($data);
        $this->view('clients/crm/crm');
        $this->layout();
    }

    // Create a new link
    public function create()
    {
        $this->form_validation->set_rules('links', 'Link', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim');
    
        if ($this->input->post() && $this->form_validation->run() !== false) {
            $data = [
                'links' => $this->input->post('links', true) ?? '', // Ensure non-null value
                'name' => $this->input->post('name', true) ?? '', // Ensure non-null value
                'description' => $this->input->post('description', true) ?? '' // Ensure non-null value
            ];
    
            $this->Misc_model->insert_crm_link($data);
            redirect('crm');
        } else {
            $this->view('clients/crm/create');
            $this->layout();
        }
    }
    


    // Edit an existing link
    public function edit($id)
{
    $this->form_validation->set_rules('links', 'Link', 'trim|required');
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_rules('description', 'Description', 'trim');

    if ($this->input->post() && $this->form_validation->run() !== false) {
        $data = [
            'links' => $this->input->post('links', true) ?? '',  // Ensure non-null value
            'name' => $this->input->post('name', true) ?? '',  // Ensure non-null value
            'description' => $this->input->post('description', true) ?? ''  // Ensure non-null value
        ];

        $this->Misc_model->update_crm_link($id, $data);
        redirect('crm');
    } else {
        $data['link'] = $this->Misc_model->get_crm_link_by_id($id);
        
        if ($data['link']) {
            $this->data($data);
            $this->view('clients/crm/edit');
            $this->layout();
        } else {
            show_404(); // Handle case where the link doesn't exist
        }
    }
}


// View the details of a link
public function details($id)
{
    // Fetch the link details from the model
    $data['link'] = $this->Misc_model->get_crm_link_by_id($id);

    // Check if the link exists
    if ($data['link']) {
        // Load the view and pass the data
        $this->data($data);
        $this->view('clients/crm/view');
        $this->layout();
    } else {
        // Show a 404 error if the link does not exist
        show_404();
    }
}

    // Delete a link
    public function delete($id)
    {
        $this->Misc_model->delete_crm_link($id);
        redirect('crm');
    }
}
