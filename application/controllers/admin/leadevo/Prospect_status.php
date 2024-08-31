<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospect_status extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/prospect_status_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['statuses'] = $this->prospect_status_model->get_all();
        $this->load->view('admin/setup/prospect_status/prospect_status', $data);
    }


    public function create()
    {

        $this->form_validation->set_rules('name','Name', 'required');
        // $this->form_validation->set_rules('description', 'Description','required');

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'is_active' => $this->input->post('is_active'),
                ];
               
                $this->prospect_status_model->insert($data);
                redirect(admin_url('leadevo/prospect_status'));
            }
        }
        $this->load->view('admin/setup/prospect_status/prospect_status_create');
    }
    public function edit($id)
    {
        $this->form_validation->set_rules('name','Name', 'required');
        
        // $this->form_validation->set_rules('description', 'Description','required');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'is_active' => $this->input->post('is_active'),
                ];
                
                $this->prospect_status_model->update($id, $data);
                redirect(admin_url('leadevo/prospect_status'));
            }
        }
        $data['status'] = $this->prospect_status_model->get($id);
        $this->load->view('admin/setup/prospect_status/prospect_status_edit', $data);
    }

    public function delete($id)
    {
        if ($this->prospect_status_model->delete($id)) {
            set_alert('success', 'Prospect status deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete prospect status.');
        }
        redirect(admin_url('leadevo/prospect_status'));
    }


    public function view($id) // Add this method to handle the view route
    {
        $data['status'] = $this->prospect_status_model->get($id);

        $this->load->view('admin/setup/prospect_status/prospect_status_view', $data); // Create this view file
    }
}
?>