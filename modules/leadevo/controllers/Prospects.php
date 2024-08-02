<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospects extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/prospects_model');
        $this->load->model('leadevo/prospect_status_model'); 
        $this->load->model('leadevo/prospect_types_model');   
        $this->load->model('leadevo/prospect_categories_model');
        $this->load->model('leadevo/acquisition_channels_model'); 
        $this->load->model('leadevo/industries_model');
    }

    public function index()
    {
        $data['prospects'] = $this->prospects_model->get_all();
        $this->load->view('leadevo/prospects/prospects', $data);
    }
    
    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'status_id' => $this->input->post('status_id'),
                'type_id' => $this->input->post('type_id'),
                'category_id' => $this->input->post('category_id'),
                'acquisition_channel_id' => $this->input->post('acquisition_channel_id'),
                'industry_id' => $this->input->post('industry_id'),
            ];
            $this->prospects_model->insert($data);
            redirect(admin_url('leadevo/prospects')); 
        }

        // Load dropdown data for the form
        $data['statuses'] = $this->prospect_status_model->get_all();
        $data['types'] = $this->prospect_types_model->get_all();
        $data['categories'] = $this->prospect_categories_model->get_all();
        $data['acquisition_channels'] = $this->acquisition_channels_model->get_all();
        $data['industries'] = $this->industries_model->get_all();

        $this->load->view('leadevo/prospects/create', $data);
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'status_id' => $this->input->post('status_id'),
                'type_id' => $this->input->post('type_id'),
                'category_id' => $this->input->post('category_id'),
                'acquisition_channel_id' => $this->input->post('acquisition_channel_id'),
                'industry_id' => $this->input->post('industry_id'),
            ];
            $this->prospects_model->update($id, $data);
            redirect(admin_url('leadevo/prospects')); 
        }

        $data['prospect'] = $this->prospects_model->get($id);

        // Load dropdown data for the form
        $data['statuses'] = $this->prospect_status_model->get_all();
        $data['types'] = $this->prospect_types_model->get_all();
        $data['categories'] = $this->prospect_categories_model->get_all();
        $data['acquisition_channels'] = $this->acquisition_channels_model->get_all();
        $data['industries'] = $this->industries_model->get_all();

        $this->load->view('leadevo/prospects/edit', $data); 
    }

    public function delete($id)
    {
        $this->prospects_model->delete($id);
        redirect(admin_url('leadevo/prospects'));
    }

    public function view($id)
    {
        $data['prospect'] = $this->prospects_model->get($id);

        // Load additional data if needed for displaying in the view
        $data['status'] = $this->prospect_status_model->get($data['prospect']->status_id);
        $data['type'] = $this->prospect_types_model->get($data['prospect']->type_id);
        $data['category'] = $this->prospect_categories_model->get($data['prospect']->category_id);
        $data['acquisition_channel'] = $this->acquisition_channels_model->get($data['prospect']->acquisition_channel_id);
        $data['industry'] = $this->industries_model->get($data['prospect']->industry_id);

        $this->load->view('leadevo/prospects/view', $data);
    }
}
