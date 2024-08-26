<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospectcategories extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/prospect_categories_model');
    }

    public function index()
    {
        $data['categories'] = $this->prospect_categories_model->get_all();
        $this->load->view('admin/setup/prospect_categories/prospect_categories', $data); // Ensure this view file exists
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => 1,
            ];
            $this->prospect_categories_model->insert($data);
            redirect(admin_url('leadevo/prospectcategories')); // Route to updated URL
        }
        $this->load->view('admin/setup/prospect_categories/prospect_categories_create');
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            ];
            $this->prospect_categories_model->update($id, $data);
            redirect(admin_url('leadevo/prospectcategories')); // Route to updated URL
        }
        $data['category'] = $this->prospect_categories_model->get($id);
        $this->load->view('admin/setup/prospect_categories/prospect_category_edit', $data);
    }

    public function delete($id)
    {
        if ($this->prospect_categories_model->delete($id)) {
            set_alert('success', 'Prospect Category deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete prospect category.');
        }
        redirect(admin_url('leadevo/prospectcategories')); // Route to updated URL
    }

    public function view($id)
    {
        $data['category'] = $this->prospect_categories_model->get($id);
        $this->load->view('admin/setup/prospect_categories/prospect_category_view', $data); // Ensure this view file exists
    }
}
