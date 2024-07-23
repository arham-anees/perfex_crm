<?php defined('BASEPATH') or exit('No direct script access allowed');

class ProspectTypes extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('prospect_types_model');
    }

    public function index()
    {
        $data['types'] = $this->prospect_types_model->get_all();
        $this->load->view('setup/prospect_types/prospect_types', $data); // Ensure this view file exists
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->prospect_types_model->insert($data);
            redirect(admin_url('leadEvo/prospecttypes')); // Updated to match route
        }
        $this->load->view('setup/prospect_types/prospect_types_create');
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->prospect_types_model->update($id, $data);
            redirect(admin_url('leadEvo/prospecttypes')); // Updated to match route
        }
        $data['type'] = $this->prospect_types_model->get($id);
        $this->load->view('setup/prospect_types/prospect_type_edit', $data);
    }
    

    public function delete($id)
    {
        if ($this->prospect_types_model->delete($id)) {
            set_alert('success', 'Prospect type deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete prospect type.');
        }
        redirect(admin_url('leadEvo/prospecttypes')); // Updated to match route
    }

    public function view($id)
    {
        $data['type'] = $this->prospect_types_model->get($id);
        $this->load->view('setup/prospect_types/prospect_type_view', $data); // Ensure this view file exists
    }
}
