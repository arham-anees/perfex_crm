<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospects extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Prospect_status_model');
        $this->load->model('leadevo/Prospect_types_model');
        $this->load->model('leadevo/Prospect_categories_model');
        $this->load->model('leadevo/Acquisition_channels_model');
        $this->load->model('leadevo/Industries_model');
    }

    public function index()
    {
        $filter = $this->input->get('filter');
        $data['prospects'] = $this->Prospects_model->get_all_by_filter($filter);

        $data['prospects'] = $this->Prospects_model->get_all();

        $this->data($data);
        $this->view('clients/prospects/prospects');
        $this->layout();
    }

    public function prospect($id)
    {
        $data['prospect'] = $this->Prospects_model->get($id);
        $this->data($data);
        $this->view('clients/prospects/prospect_view');
        $this->layout();
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

            $this->Prospects_model->insert($data);
            redirect('prospects');
        } else {
            $data['statuses'] = $this->Prospect_status_model->get_all();
            $data['types'] = $this->Prospect_types_model->get_all();
            $data['categories'] = $this->Prospect_categories_model->get_all();
            $data['acquisition_channels'] = $this->Acquisition_channels_model->get_all();
            $data['industries'] = $this->Industries_model->get_all();
            $this->data($data);
            $this->view('clients/prospects/prospect_create');
            $this->layout();
        }
    }

    public function delete($id)
    {
        if ($this->Prospects_model->delete($id)) {
            set_alert('success', 'Prospect deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete Prospect.');
        }
        redirect('prospects');
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
            $this->Prospects_model->update($id, $data);
            redirect('prospects');
        } else {
            $data['prospect'] = $this->Prospects_model->get($id);
            $data['statuses'] = $this->Prospect_status_model->get_all();
            $data['types'] = $this->Prospect_types_model->get_all();
            $data['categories'] = $this->Prospect_categories_model->get_all();
            $data['acquisition_channels'] = $this->Acquisition_channels_model->get_all();
            $data['industries'] = $this->Industries_model->get_all();
            $this->data($data);
            $this->view('clients/prospects/edit');
            $this->layout();
        }
    }
}
