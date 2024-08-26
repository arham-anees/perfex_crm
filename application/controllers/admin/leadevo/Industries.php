<?php defined('BASEPATH') or exit('No direct script access allowed');

class Industries extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/industries_model');
    }

    public function index()
    {
        $data['industries'] = $this->industries_model->get_all();
        $this->load->view('admin/setup/industries/industries', $data);
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => 1,
            ];
            $this->industries_model->insert($data);
            redirect(admin_url('leadevo/industries'));
        }
        $this->load->view('admin/setup/industries/industries_create');
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            ];
            $this->industries_model->update($id, $data);
            redirect(admin_url('leadevo/industries'));
        }
        $data['industry'] = $this->industries_model->get($id);
        $this->load->view('admin/setup/industries/industries_edit', $data);
    }

    public function delete($id)
    {
        if ($this->industries_model->delete($id)) {
            set_alert('success', 'Industry deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete industry.');
        }
        redirect(admin_url('leadevo/industries'));
    }

    public function view($id)
    {
        $data['industry'] = $this->industries_model->get($id);
        $this->load->view('admin/setup/industries/industries_view', $data);
    }
}
