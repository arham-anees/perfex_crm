<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospect extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Prospects_model');
    }

    public function index()
    {
        $data['prospects'] = $this->Prospects_model->get_all();
        $this->load->view('client/prospects/prospects', $data);
    }

    public function view($id)
    {
        $data['prospect'] = $this->Prospects_model->get($id);
        $this->load->view('client/prospects/prospect_view', $data);
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
            // $data['prospect_types'] = $this->Prospects_model->get_prospect_types();
            // $data['prospect_categories'] = $this->Prospects_model->get_prospect_categories();
            // $data['industries'] = $this->Prospects_model->get_industries();
            // $this->load->view('client/prospects/prospect_create', $data);
            $this->load->view('client/prospects/prospect_create');
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
        $this->load->view('client/prospects/prospects');
    }

                

    
}