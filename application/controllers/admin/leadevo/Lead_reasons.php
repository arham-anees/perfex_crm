<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lead_reasons extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/lead_reasons_model');
        $this->load->library('form_validation');

    }

    public function index()
    {
        $data['reasons'] = $this->lead_reasons_model->get_all('');
        $this->load->view('admin/setup/lead_reasons/lead_reasons', $data);
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
                $this->lead_reasons_model->insert($data);
                redirect(admin_url('leadevo/lead_reasons'));
            }
        }
        $this->load->view('admin/setup/lead_reasons/lead_reason_create');
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
                $this->lead_reasons_model->update($id, $data);
                redirect(admin_url('leadevo/lead_reasons'));
            }
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

    public function get_report_hours()
    {
        echo json_encode(['status' => 'success', 'data' => (get_option('leadevo_report_hours') ?? 0)]);
    }

    public function set_report_hours()
    {
        $hours = $this->input->post('report_hours');
        if (isset($hours) && !empty($hours)) {
            update_option('leadevo_report_hours', $hours);
            echo json_encode(['status' => 'success', 'message' => 'Report Hours updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request or Invalid values']);
        }
    }
}
