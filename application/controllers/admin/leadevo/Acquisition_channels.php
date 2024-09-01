<?php defined('BASEPATH') or exit('No direct script access allowed');

class Acquisition_channels extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/acquisition_channels_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['channels'] = $this->acquisition_channels_model->get_all('');
        $this->load->view('admin/setup/acquisition_channels/acquisition_channels', $data);
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
                $this->acquisition_channels_model->insert($data);
                redirect(admin_url('leadevo/acquisition_channels'));
            }
        }
        $this->load->view('admin/setup/acquisition_channels/acquisition_channel_create');
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
                $this->acquisition_channels_model->update($id, $data);
                redirect(admin_url('leadevo/acquisition_channels'));
            }
        }
        $data['channel'] = $this->acquisition_channels_model->get($id);
        $this->load->view('admin/setup/acquisition_channels/acquisition_channel_edit', $data);
    }

    public function delete($id)
    {
        if ($this->acquisition_channels_model->delete($id)) {
            set_alert('success', 'Acquisition Channel deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete acquisition channel.');
        }
        redirect(admin_url('leadevo/acquisition_channels'));
    }

    public function view($id)
    {
        $data['channel'] = $this->acquisition_channels_model->get($id);
        $this->load->view('admin/setup/acquisition_channels/acquisition_channel_view', $data);
    }
}
