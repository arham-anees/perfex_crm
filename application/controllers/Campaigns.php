<?php defined('BASEPATH') or exit('No direct script access allowed');

class Campaigns extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Industries_model');

    }

    public function index()
    {
        $data['campaigns'] = $this->Campaigns_model->get_all_client();
        $data['industries'] = $this->Industries_model->get_all(); // Fetch all industries
        $data['countries'] = $this->Campaigns_model->get_all_countries();

        $this->data($data);
        $this->view('clients/campaigns/campaign');
        $this->layout();

    }

    function arrayToStringWithQuotes($array)
    {
        // Convert each integer to a string and wrap it in single quotes
        $stringArray = array_map(function ($value) {
            return '"' . (string) $value . '"';
        }, $array);

        // Convert the array to a string with comma as the delimiter
        return '[' . implode(',', $stringArray) . ']';
    }
    public function create_campaign()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            if ($this->input->post()) {

                $start_date = $this->input->post('start_date');
                $end_date = $this->input->post('end_date');
                $current_date = date('Y-m-d');

                // Validate dates
                if ($start_date < $current_date) {
                    log_message('error', '---->Start date cannot be before the current date.');
                    $this->session->set_flashdata('error', 'Start date cannot be before the current date.');
                    // redirect(site_url('campaigns/create'));
                    die;
                }
                if ($end_date < $start_date) {
                    log_message('error', '---->End date cannot be before the start date..');
                    $this->session->set_flashdata('error', 'End date cannot be before the start date.');
                    // redirect(site_url('campaigns/create'));
                    die;
                }
                if ($end_date < $current_date) {
                    log_message('error', '---->End date cannot be before the current date.');
                    $this->session->set_flashdata('error', 'End date cannot be before the current date.');
                    // redirect(site_url('campaigns/create'));
                    die;
                }

                // Collect data from POST request
                $data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'start_date' => $this->input->post('start_date'),
                    'end_date' => $this->input->post('end_date'),
                    'status_id' => 1,
                    'budget' => $this->input->post('budget'),
                    'is_active' => 1,
                    'industry_id' => $this->input->post('industry_id'),
                    'deal' => (int) $this->input->post('deal'),
                    'verify_by_staff' => (int) $this->input->post('verify_by_staff'),
                    'verify_by_sms' => (int) $this->input->post('verify_by_sms'),
                    'verify_by_whatsapp' => (int) $this->input->post('verify_by_whatsapp'),
                    'verify_by_coherence' => (int) $this->input->post('verify_by_coherence'),
                    'client_id' => get_client_user_id()
                ];

                $country_ids = $this->input->post('country_id');
                if ($country_ids) {
                    // Convert comma-separated string to array
                    $country_ids_array = explode(',', $country_ids);
                    // Format array using the custom function
                    $data['country_id'] = $this->arrayToStringWithQuotes($country_ids_array);
                }

                $caps = $this->input->post('caps[]');
                if ($caps) {
                    // Convert comma-separated string to array
                    // $caps_array = explode(',', $caps);
                    // Format array using the custom function
                    $data['timings'] = $this->arrayToStringWithQuotes($caps);
                }

                if (isset($data['id']))
                    unset($data['id']);
                $this->Campaigns_model->insert($data);


                // set_alert('success', 'Campaign created successfully.');
                // Return success response
                echo json_encode(['success' => true, 'message' => 'Campaign created successfully.']);

                // redirect(site_url('campaigns'));
            }
        }
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $current_date = date('Y-m-d');

            // Validate dates
            if ($start_date < $current_date) {
                $this->session->set_flashdata('error', 'Start date cannot be before the current date.');
                redirect(site_url('campaigns/edit/' . $id));
            }
            if ($end_date < $start_date) {
                $this->session->set_flashdata('error', 'End date cannot be before the start date.');
                redirect(site_url('campaigns/edit/' . $id));
            }
            if ($end_date < $current_date) {
                $this->session->set_flashdata('error', 'End date cannot be before the current date.');
                redirect(site_url('campaigns/edit/' . $id));
            }

            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'status_id' => $this->input->post('status_id'),
                'budget' => $this->input->post('budget'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
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
            redirect(site_url('campaigns'));
        }
        $data['campaign'] = $this->Campaigns_model->get($id);
        $data['statuses'] = $this->Campaigns_model->get_campaign_statuses();
        $data['industries'] = $this->Industries_model->get_all(); // Fetch all industries
        $this->data($data);
        $this->view('clients/campaigns/campaign_edit');
        $this->layout();
    }

    public function delete($id)
    {
        if ($this->Campaigns_model->delete($id)) {
            set_alert('success', 'Campaign deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete campaign.');
        }
        redirect(site_url('campaigns'));
    }

    public function campaign($id)
    {
        $data['campaign'] = $this->Campaigns_model->get($id);
        // $this->load->view('setup/campaigns/campaign_view', $data);
        $this->data($data);
        $this->view('clients/campaigns/campaign_view');
        $this->layout();
    }

}
