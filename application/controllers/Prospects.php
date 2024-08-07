<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospects extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Misc_model');
    }

    public function index()
    {
        $filter = $this->input->get('filter');

        $data['prospects'] = $this->Prospects_model->get_all_client($filter);

        $this->data($data);
        $this->view('clients/prospects/prospects');
        $this->layout();
    }

    public function details($id)
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
                'prospect_name' => $this->input->post('prospect_name'),
                'status' => $this->input->post('status'),
                'type' => $this->input->post('type'),
                'category' => $this->input->post('category'),
                'industry' => $this->input->post('industry'),
                'aquisition' => $this->input->post('aquisition'),
            ];

            $this->Prospects_model->insert($data);
            redirect('leadevo/prospect');
        } else {
            $data['prospect_types'] = $this->Misc_model->get_prospect_types();
            $data['industry_categories'] = $this->Misc_model->get_industry_categories();
            $data['industries'] = $this->Misc_model->get_industries();
            $data['acquisition_channels'] = $this->Misc_model->get_acquisition_channels();
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
        // redirect(admin_url('leadevo/prospect'));
        // $this->load->view('client/prospects/prospects');
        // $this->data($data);
        redirect('clients/prospects/prospects');
        // $this->layout();
    }




}