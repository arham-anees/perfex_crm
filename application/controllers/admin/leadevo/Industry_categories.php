<?php defined('BASEPATH') or exit('No direct script access allowed');

class Industry_categories extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/industry_categories_model');
    }

    public function index()
    {
        $data['categories'] = $this->industry_categories_model->get_all();
        $this->load->view('admin/setup/industry_categories/industry_categories', $data);
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->industry_categories_model->insert($data);
            redirect(admin_url('leadevo/industry_categories'));
        }
        $this->load->view('admin/setup/industry_categories/industry_categories_create');
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            ];
            $this->industry_categories_model->update($id, $data);
            redirect(admin_url('leadevo/industry_categories'));
        }
        $data['category'] = $this->industry_categories_model->get($id);
        $this->load->view('admin/setup/industry_categories/industry_category_edit', $data);
    }

    public function delete($id)
    {
        if ($this->industry_categories_model->delete($id)) {
            set_alert('success', 'Industry Category deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete industry category.');
        }
        redirect(admin_url('leadevo/industry_categories'));
    }

    public function view($id)
    {
        $data['category'] = $this->industry_categories_model->get($id);
        $this->load->view('admin/setup/industry_categories/industry_category_view', $data);
    }
}
