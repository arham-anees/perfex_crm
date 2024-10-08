<?php defined('BASEPATH') or exit('No direct script access allowed');

class Campaigns extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Industries_model');
        $this->load->model('leadevo/campaign_statuses_model');

        if (!staff_can('manage_campaign', 'leadevo')) {
            access_denied();
        }
    }

    public function index()
    {
         if ($this->input->post()) {

            $search = array(
                'industry_name' => $this->input->post('industry'),
                'acquisition_channel_id' => $this->input->post('acquisition'),
                'budget_range_from' => $this->input->post('budget_range_start'),
                'budget_range_to' => $this->input->post('budget_range_end'),
                'generated_from' => $this->input->post('start_date'),
                'generated_to' => $this->input->post('end_date'),
                'status' => $this->input->post('status'),
                'deal' => $this->input->post('deal'),

                // 'zip_codes' => $this->input->post('zip_codes')
            );
            //    echo "<pre>";
            // print_r($search);
            // exit;
            $data['campaigns'] = $this->Campaigns_model->get_all($search);
        } else {

            $data['campaigns'] = $this->Campaigns_model->get_all('');
        }
        $data['statuses'] = $this->campaign_statuses_model->get_all('');
        // echo "<pre>";
        // print_r($data['statuses']);
        // exit;
         // Fetch all industries
        $data['countries'] = $this->Campaigns_model->get_all_countries();
        // $data['campaigns'] = $this->Campaigns_model->get_all();
        $data['industries'] = $this->Industries_model->get_all(); // Fetch all industries
        $this->load->view('admin/leadevo/campaigns/campaign', $data);
    }
    public function matching()
    {
        $prospect_id = $this->input->get('prospect_id');
        $campaigns = $this->Campaigns_model->get_matching($prospect_id);
        echo json_encode(array('status' => 'success', 'data' => json_encode($campaigns)));
    }


    public function edit($id)
    {
        $campaign = $this->Campaigns_model->get($id);
        $status = $this->Campaigns_model->get_campaign_statuses();
        $status_name = '';
        foreach ($status as $stat) {
            if ($stat['id'] == $campaign->status_id) {
                $status_name = $stat['name'];
                break;
            }
        }
        if ($status_name === 'Active') {
            set_alert('danger', 'You cannot edit this campaign. It is already started');
            redirect(admin_url('campaigns'));
        }
        if ($status_name === 'Completed') {
            set_alert('danger', 'You do not have permission to edit this campaign.');
            redirect(admin_url('campaigns'));
        }
        if ($campaign->start_date < date('Y-m-d')) {
            set_alert('danger', 'You cannot edit this campaign. It is either already started or completed.');
            redirect(admin_url('campaigns'));
        }
        // Proceed with editing logic if allowed
        if ($this->input->post()) {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $current_date = date('Y-m-d');

            // Validate dates
            if ($start_date < $current_date) {
                $this->session->set_flashdata('error', 'Start date cannot be before the current date.');
                redirect(admin_url('campaigns/edit/' . $id));
            }
            if ($end_date < $start_date) {
                $this->session->set_flashdata('error', 'End date cannot be before the start date.');
                redirect(admin_url('campaigns/edit/' . $id));
            }
            if ($end_date < $current_date) {
                $this->session->set_flashdata('error', 'End date cannot be before the current date.');
                redirect(admin_url('campaigns/edit/' . $id));
            }

            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'status_id' => $this->input->post('status_id'),
                'budget' => $this->input->post('budget'),
                'industry_id' => $this->input->post('industry_id'),
                'country_id' => $this->input->post('country_id'),
                'deal' => $this->input->post('deal') ? 1 : 0,
                'verify_by_staff' => $this->input->post('verify_by_staff') ? 1 : 0,
                'verify_by_sms' => $this->input->post('verify_by_sms') ? 1 : 0,
                'verify_by_whatsapp' => $this->input->post('verify_by_whatsapp') ? 1 : 0,
                'verify_by_coherence' => $this->input->post('verify_by_coherence') ? 1 : 0,
                'timings' => $this->input->post('timings')

            ];
            $this->Campaigns_model->update($id, $data);
            set_alert('success', 'Campaign updated successfully.');
            redirect(admin_url('campaigns'));
        }
        $data['campaign'] = $this->Campaigns_model->get($id);
        $data['statuses'] = $this->Campaigns_model->get_campaign_statuses();
        $data['industries'] = $this->Industries_model->get_all(); // Fetch all industries
        $this->load->view('admin/leadevo/campaigns/campaign_edit', $data);
    }

    public function delete($id)
    {
        $campaign = $this->Campaigns_model->get($id);
        $status = $this->Campaigns_model->get_campaign_statuses();
        $status_name = '';
        foreach ($status as $stat) {
            if ($stat['id'] == $campaign->status_id) {
                $status_name = $stat['name'];
                break;
            }
        }
        if ($status_name === 'Active') {
            set_alert('danger', 'You cannot delete this campaign. It is already started');
            redirect(admin_url('campaigns'));
        }
        if ($status_name === 'Completed') {
            set_alert('danger', 'You do not have permission to delete this campaign.');
            redirect(admin_url('campaigns'));
        }
        if ($campaign->start_date < date('Y-m-d')) {
            set_alert('danger', 'You cannot delete this campaign. It is either already started or completed.');
            redirect(admin_url('campaigns'));
        }

        // Proceed with deletion logic if allowed
        $delete = $this->Campaigns_model->delete($id);

        if ($delete) {
            set_alert('success', 'Campaign deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete campaign.');
        }
        redirect(admin_url('campaigns'));
    }

    public function view($id)
    {
        $data['campaign'] = $this->Campaigns_model->get($id);
        $this->load->view('admin/leadevo/campaigns/campaign_view', $data);
    }
}
