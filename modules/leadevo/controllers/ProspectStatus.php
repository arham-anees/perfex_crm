<?php defined('BASEPATH') or exit('No direct script access allowed');

class ProspectStatus extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('prospect_status_model');
    }

    public function index()
    {
        $data['statuses'] = $this->prospect_status_model->get_all();
        $this->load->view('setup/prospect_status_view', $data);
    }

  
    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->prospect_status_model->insert($data);
            redirect(admin_url('leadEvo/prospectstatus'));
        }
        $this->load->view('setup/prospect_status_create');
    }
    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->prospect_status_model->update($id, $data);
            redirect(admin_url('leadEvo/prospectstatus'));
        }
        $data['status'] = $this->prospect_status_model->get($id);
        $this->load->view('setup/prospect_status_edit', $data);
    }

    public function delete($id)
{
    if ($this->prospect_status_model->delete($id)) {
        set_alert('success', 'Prospect status deleted successfully.');
    } else {
        set_alert('danger', 'Failed to delete prospect status.');
    }
    redirect(admin_url('leadEvo/prospectstatus'));
}


    public function view($id) // Add this method to handle the view route
    {
        $data['status'] = $this->prospect_status_model->get($id);
        $this->load->view('setup/prospect_status_view_single', $data); // Create this view file
    }
}
?>
