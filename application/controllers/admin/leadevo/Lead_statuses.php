<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lead_statuses extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Lead_statuses_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['statuses'] = $this->Lead_statuses_model->get_all();
        $this->load->view('admin/setup/lead_statuses/index', $data);
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
                    'is_active' => 1,
                ];
                $this->Lead_statuses_model->insert($data);
                redirect(admin_url('leadevo/lead_statuses'));
            }
        }
        $this->load->view('admin/setup/lead_statuses/lead_status_create');
    }

    public function edit($id)
    {
        $this->form_validation->set_rules('name','Name', 'required');
        // $this->form_validation->set_rules('description', 'Description','required');

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description')
                ];
                $this->Lead_statuses_model->update($id, $data);
                redirect(admin_url('leadevo/lead_statuses'));
            }
        }
        $data['status'] = $this->Lead_statuses_model->get($id);
        $this->load->view('admin/setup/lead_statuses/lead_status_edit', $data);
    }

    public function delete($id)
    {
        if ($this->Lead_statuses_model->delete($id)) {
            set_alert('success', 'Lead Status deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete lead status.');
        }
        redirect(admin_url('leadevo/lead_statuses'));
    }

    public function view($id)
    {
        $data['status'] = $this->Lead_statuses_model->get($id);
        $this->load->view('admin/setup/lead_statuses/lead_status_view', $data);
    }
}
