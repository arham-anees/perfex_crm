<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lead_reasons extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/lead_reasons_model');
    }

    public function index()
    {
        $data['reasons'] = $this->lead_reasons_model->get_all();
        $this->load->view('admin/setup/lead_reasons/lead_reasons', $data);
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => 1,
            ];
            $this->lead_reasons_model->insert($data);
            redirect(admin_url('leadevo/lead_reasons'));
        }
        $this->load->view('admin/setup/lead_reasons/lead_reason_create');
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            ];
            $this->lead_reasons_model->update($id, $data);
            redirect(admin_url('leadevo/lead_reasons'));
        }
        $data['reason'] = $this->lead_reasons_model->get($id);
        $this->load->view('admin/setup/lead_reasons/lead_reason_edit', $data);
    }

    public function delete($id)
    {
        if ($this->lead_reasons_model->delete($id)) {
            set_alert('success', 'Lead Reason deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete lead reason.');
        }
        redirect(admin_url('leadevo/lead_reasons'));
    }

    public function view($id)
    {
        $data['reason'] = $this->lead_reasons_model->get($id);
        $this->load->view('admin/setup/lead_reasons/lead_reason_view', $data);
    }
}
