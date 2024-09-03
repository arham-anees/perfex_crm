<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospect_sources extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        //load some models
        $this->load->model('leadevo/Prospect_sources_model');
        $this->load->library('form_validation');

    }

    public function index()
    {
        //get the search parameter from the URL
        $search = $this->input->get('search');

        // Fetch prospect sources based on the search parameter
        $data['prospect_sources'] = $this->Prospect_sources_model->get_prospect_sources($search);

        // Pass the search term to the view
        $data['search'] = $search;
        
        // Fetch all prospect sources
        // $data['prospect_sources'] = $this->Prospect_sources_model->get_prospect_sources();

        // Load the view and pass the data
        $this->load->view('admin/prospect_sources/index', $data);

    }

    //get perspective source by id
    public function get($id)
    {
        // log_message('error', 'prospectsource get() called');

        $data['prospect_source'] = $this->Prospect_sources_model->get_prospect_source($id);
        $this->load->view('admin/prospect_sources/view', $data);
    }

    // Add a new prospect source
    public function add()
    {
        // log_message('error', 'prospectsource add() called');
        $this->form_validation->set_rules('name','Name', 'required');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'is_active' => $this->input->post('is_active'),
                ];

                $this->Prospect_sources_model->insert_prospect_source($data);
                redirect('admin/leadevo/prospect_sources');
            }
        } else {
            $this->load->view('admin/prospect_sources/add_prospect_source');
        }
    }

    // Edit a prospect source
    public function edit($id)
    {
        $this->form_validation->set_rules('name','Name', 'required');

        if ($this->input->post()) {
             if ($this->form_validation->run() !== false) {
                $data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'is_active' => $this->input->post('is_active'),
                ];


                if ($this->Prospect_sources_model->update_prospect_source($id, $data)) {
                    set_alert('success', 'Prospect Source updated successfully.');
                } else {
                    set_alert('danger', 'Failed to update Prospect Source.');
                }
                // redirect('admin/leadevo/prospect_sources');
                redirect(admin_url('leadevo/prospect_sources'));
            }
        } else {
            $data['prospect_source'] = $this->Prospect_sources_model->get_prospect_source($id);
            $this->load->view('admin/prospect_sources/edit_prospect_source', $data);
        }
    }

    // Delete a prospect source
    public function delete($id)
    {
        if($this->Prospect_sources_model->delete_prospect_source($id)) {
            set_alert('success', 'Prospect Source deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete Prospect Source.');
        }

        redirect('admin/leadevo/prospect_sources');
    }

    // public function get_prospet_sources_effectiveness()
    // {
    //             // Fetch the prospect sources effectiveness data
    //             $data['prospect_sources_effectiveness'] = $this->Prospect_sources_model->get_prospect_sources_effectiveness();

    //             // Load the view and pass the data
    //             $this->load->view('admin/prospect_sources/index', $data);
    // }





}