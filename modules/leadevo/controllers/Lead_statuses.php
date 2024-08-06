<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lead_statuses extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lead_statuses_model');
    }

    public function index()
    {
        $data['statuses'] = $this->lead_statuses_model->get_all();
        $this->load->view('setup/lead_statuses/lead_statuses', $data);
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->lead_statuses_model->insert($data);
            redirect(admin_url('leadevo/lead_statuses'));
        }
        $this->load->view('setup/lead_statuses/lead_status_create');
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->lead_statuses_model->update($id, $data);
            redirect(admin_url('leadevo/lead_statuses'));
        }
        $data['status'] = $this->lead_statuses_model->get($id);
        $this->load->view('setup/lead_statuses/lead_status_edit', $data);
    }

    public function delete($id)
    {
        if ($this->lead_statuses_model->delete($id)) {
            set_alert('success', 'Lead Status deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete lead status.');
        }
        redirect(admin_url('leadevo/lead_statuses'));
    }

    public function view($id)
    {
        $data['status'] = $this->lead_statuses_model->get($id);
        $this->load->view('setup/lead_statuses/lead_status_view', $data);
    }
}
