<?php defined('BASEPATH') or exit('No direct script access allowed');

class Industry_categories extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Industry_categories_model');
        $this->load->model('leadevo/Industries_model');
    }

    public function index()
    {
        $data['categories'] = $this->Industry_categories_model->get_all();
        $data['industries'] = $this->Industries_model->get_all();
        $this->load->view('admin/setup/industry_categories/industry_categories', $data);
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'min_price' => $this->input->post('min_price'),
                'min_market_price' => $this->input->post('min_market_price'),
                'description' => $this->input->post('description'),
                'industry_id' => $this->input->post('industry_id'), // Capture the industry ID
            ];
            $this->Industry_categories_model->insert($data);
            redirect(admin_url('leadevo/industry_categories'));
        }

        // Fetch all industries to display in the form
        $data['industries'] = $this->Industries_model->get_all();
        $this->load->view('admin/setup/industry_categories/industry_categories_create', $data);
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'min_price' => $this->input->post('min_price'),
                'min_market_price' => $this->input->post('min_market_price'),
                'description' => $this->input->post('description'),
                'industry_id' => $this->input->post('industry_id'),
            ];
            $this->Industry_categories_model->update($id, $data);
            redirect(admin_url('leadevo/industry_categories'));
        }

        // Fetch the current category and all industries
        // $data['category'] = $this->Industry_categories_model->get($id);
        $category = $this->Industry_categories_model->get($id);
        $data['category'] = (array) $category;
        $data['industries'] = $this->Industries_model->get_all(); // Get all industries
        $this->load->view('admin/setup/industry_categories/industry_category_edit', $data);
    }

    public function delete($id)
    {
        if ($this->Industry_categories_model->delete($id)) {
            set_alert('success', 'Industry Category deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete industry category.');
        }
        redirect(admin_url('leadevo/industry_categories'));
    }

    public function view($id)
    {
        // $data['category'] = $this->Industry_categories_model->get($id);
        $category = $this->Industry_categories_model->get($id);
        $data['category'] = (array) $category;
        $this->load->view('admin/setup/industry_categories/industry_category_view', $data);
    }
}

