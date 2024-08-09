<?php defined('BASEPATH') or exit('No direct script access allowed');

class Campaign_statuses extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/campaign_statuses_model');
    }

    public function index()
    {
        $data['statuses'] = $this->campaign_statuses_model->get_all();
        $this->load->view('admin/setup/campaign_statuses/campaign_statuses', $data);
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->campaign_statuses_model->insert($data);
            redirect(admin_url('leadevo/campaign_statuses'));
        }
        $this->load->view('admin/setup/campaign_statuses/campaign_status_create');
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->campaign_statuses_model->update($id, $data);
            redirect(admin_url('leadevo/campaign_statuses'));
        }
        $data['status'] = $this->campaign_statuses_model->get($id);
        $this->load->view('admin/setup/campaign_statuses/campaign_status_edit', $data);
    }

    public function delete($id)
    {
        if ($this->campaign_statuses_model->delete($id)) {
            set_alert('success', 'Campaign Status deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete campaign status.');
        }
        redirect(admin_url('leadevo/campaign_statuses'));
    }

    public function view($id)
    {
        $data['status'] = $this->campaign_statuses_model->get($id);
        $this->load->view('admin/setup/campaign_statuses/campaign_status_view', $data);
    }
}
