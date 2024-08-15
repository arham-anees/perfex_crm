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
        $this->load->model('Leads_model');
        $this->load->model('leadevo/Reported_Prospects_model');
    }

    public function index()
    {
        $filter = $this->input->get('filter');
        if ($filter)
            $data['prospects'] = $this->Prospects_model->get_all_by_filter($filter);
        else
            $data['prospects'] = $this->Prospects_model->get_all();



        $this->data($data);
        $this->view('clients/prospects/prospects');
        $this->layout();
    }

    public function fetch_to_send()
    {
        $id = $this->input->get('id');
        if ($id) {
            $lead = $this->Leads_model->get_to_send($id);
            if ($lead) {
                $str = json_encode($lead);
                echo json_encode(['status' => 'success', 'data' => base64_encode($str)]);
                return;
            }
        }

        echo json_encode(['status' => 'error', 'data' => null]);

    }
    public function purchased()
    {


        $this->load->model('contracts_model');
        $data['contract_types'] = $this->contracts_model->get_contract_types();
        $data['groups'] = $this->clients_model->get_groups();
        $data['title'] = _l('clients');

        $this->load->model('proposals_model');
        $data['proposal_statuses'] = $this->proposals_model->get_statuses();

        $this->load->model('invoices_model');
        $data['invoice_statuses'] = $this->invoices_model->get_statuses();

        $this->load->model('estimates_model');
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();

        $this->load->model('projects_model');
        $data['project_statuses'] = $this->projects_model->get_project_statuses();

        $data['customer_admins'] = $this->clients_model->get_customers_admin_unique_ids();

        $whereContactsLoggedIn = '';


        $data['contacts_logged_in_today'] = $this->clients_model->get_contacts('', 'last_login LIKE "' . date('Y-m-d') . '%"' . $whereContactsLoggedIn);

        $data['countries'] = $this->clients_model->get_clients_distinct_countries();
        $data['table'] = $this->clients_model->get_purchased();
        $data['reasons'] = $this->Prospects_model->get_Reasons();
        $this->data($data);

        $this->view('clients/prospects/purchased');
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
                'desired_amount' => $this->input->post('desired_amount'),
                'min_amount' => $this->input->post('min_amount'),
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
                'desired_amount' => $this->input->post('desired_amount'),
                'min_amount' => $this->input->post('min_amount'),
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

    public function reported()
    {
        $filter = $this->input->get('filter');

        if ($filter) {
            $data['reported_prospects'] = $this->Reported_Prospects_model->get_all_by_filter($filter);
        } else {
            $data['reported_prospects'] = $this->Reported_Prospects_model->get_all();
        }

        $this->data($data);
        $this->view('clients/prospects/prospect_reported');
        $this->layout();
    }

    public function view_reported($id)
    {
        $this->load->model('leadevo/Reported_Prospects_model'); // Load the model
        $data['reported_prospect'] = $this->Reported_Prospects_model->get($id);

        if (!$data['reported_prospect']) {
            show_404(); // If no data found, show 404 page
        }

        $this->data($data);
        $this->view('clients/prospects/prospect_reported_view');
        $this->layout();
    }


    function submit_report()
    {
        if ($this->input->post()) {
            $data = [
                'evidence' => $this->input->post('evidence'),
                'reason' => $this->input->post('reason'),
                'client_id' => $this->input->post('client_id'),
                'lead_id' => $this->input->post('prospect_id'),
            ];
            $this->Prospects_model->submit_report($data);
            $this->view('clients/prospects/purchased');
        } else {


        }
    }

}
