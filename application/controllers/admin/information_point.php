<?php defined('BASEPATH') or exit('No direct script access allowed');

class Information_point extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Information_model');
    }

    public function index()
    {
        $data['information_point'] = $this->Information_model->get_all();
        $this->load->view('admin/leadevo/information-point/information', $data);
    }
  

    public function create()
    {
        if ($this->input->is_ajax_request()) {
            $data = [
                'info_key' => $this->input->post('info_key'),
                'info' => $this->input->post('info'),
            ];
    
            if ($this->Information_model->insert($data)) {
                $response = ['status' => 'success', 'message' => 'Information point created successfully.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to create information point.'];
            }
    
            echo json_encode($response);
            return;
        }
    
        // Load the view for creating an information point
        $this->load->view('admin/leadevo/information-point/information_create');
    }
    


    public function edit($id)
    {
        if ($this->input->is_ajax_request()) {
            $data = [
                'info_key' => $this->input->post('info_key'),
                'info' => $this->input->post('info'),
            ];
    
            if ($this->Information_model->update($id, $data)) {
                $response = ['status' => 'success', 'message' => 'Information point updated successfully.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to update information point.'];
            }
    
            echo json_encode($response);
            return;
        }
    
        $data['informationpoint'] = $this->Information_model->get($id);
        $this->load->view('admin/leadevo/information-point/information_edit', $data);
    }
    
    public function delete($id)
    {
        if ($this->Information_model->delete($id)) {
            set_alert('success', 'Information deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete.');
        }
        redirect(admin_url('Information_point'));
    }

    public function view($id)
    {
        $data['infomationpoint'] = $this->Information_model->get($id);
        $this->load->view('admin/leadevo/information-point/information_view', $data);
    }

    
}
