<?php defined('BASEPATH') or exit('No direct script access allowed');

class Industries extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/industries_model');
        $this->load->model('leadevo/industry_categories_model'); // Load industry categories model
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['industries'] = $this->industries_model->get_all('');
        $data['categories'] = $this->industry_categories_model->get_all('');
        $this->load->view('admin/setup/industries/industries', $data);
    }

    public function create()
    {
        $this->form_validation->set_rules('name','Name', 'required');
        // $this->form_validation->set_rules('description', 'Description','required');
        $this->form_validation->set_rules('category_id', 'Select Category','required');

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'category_id' => $this->input->post('category_id'),

                    'is_active' => $this->input->post('is_active'),
                ];
                $this->industries_model->insert($data);
                redirect(admin_url('leadevo/industries'));
            }
        }
        
        // Fetch categories to show in the create form
        $data['categories'] = $this->industry_categories_model->get_all('');
        $this->load->view('admin/setup/industries/industries_create', $data);
    }

    public function edit($id)
    {
        $this->form_validation->set_rules('name','Name', 'required');
        // $this->form_validation->set_rules('description', 'Description','required');
        $this->form_validation->set_rules('category_id', 'Select Category','required');

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {

                $data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'category_id' => $this->input->post('category_id'),
                    'is_active' => $this->input->post('is_active'),

                ];
                $this->industries_model->update($id, $data);
                redirect(admin_url('leadevo/industries'));
            }
        }
        
        // Fetch the specific industry and categories to show in the edit form
        $data['industry'] = $this->industries_model->get($id);
        $data['categories'] = $this->industry_categories_model->get_all();
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
