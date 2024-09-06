<?php defined('BASEPATH') or exit('No direct script access allowed');

class Campaigns extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Industries_model');
        $this->load->model('Client_invoices_model');
        $this->load->model('leadevo/campaign_statuses_model');
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
        if (is_client_logged_in() && !is_contact_email_verified()) {
            redirect(site_url('verification'));
        }
        if (!is_onboarding_completed()) {
            redirect(site_url('onboarding'));
        }

    }


    public function index()
    {
        //    echo "<pre>";
        // print_r($this->input->post());
        // exit;

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
            // print_r($this->input->post());
            // exit;
            $data['campaigns'] = $this->Campaigns_model->get_all_client($search);
        } else {

            $data['campaigns'] = $this->Campaigns_model->get_all_client('');
        }
        $data['statuses'] = $this->campaign_statuses_model->get_all('');
        // echo "<pre>";
        // print_r($data['statuses']);
        // exit;
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
                    'status_id' => 3,//planned
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
                $campaign_id = $this->Campaigns_model->insert($data);



                // Return success response
                $budget = $data['budget'];
                $invoice = $this->checkout($budget, $campaign_id, $data);


                $this->Campaigns_model->update_invoice($campaign_id, $invoice['id']);
                $hash =  $this->Campaigns_model->get_hash($invoice['id']);

                echo json_encode(['status' => 'success', 'data' => site_url('invoice/' . $invoice['id'] . '/' . $hash)]);
                // echo json_encode(['success' => true, 'message' => 'Campaign created successfully.']);
                set_alert('success', 'Campaign created successfully.');
            }
        }
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
            redirect(site_url('campaigns'));
        }
        if ($status_name === 'Completed') {
            set_alert('danger', 'You do not have permission to edit this campaign.');
            redirect(site_url('campaigns'));
        }
        if ($campaign->start_date < date('Y-m-d')) {
            set_alert('danger', 'You cannot edit this campaign. It is either already started or completed.');
            redirect(site_url('campaigns'));
        }

        if ($this->input->post()) {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $current_date = date('Y-m-d');



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
            redirect(site_url('campaigns'));
        }
        if ($status_name === 'Completed') {
            set_alert('danger', 'You do not have permission to delete this campaign.');
            redirect(site_url('campaigns'));
        }
        if ($campaign->start_date < date('Y-m-d')) {
            set_alert('danger', 'You cannot delete this campaign. It is either already started or completed.');
            redirect(site_url('campaigns'));
        }



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

    public function checkout($total, $campaign_id, $campaign)
    {
        $client_id = get_client_user_id();
        // hooks()->do_action('after_prospect_purchased', ['client_id' => get_client_user_id(), 'prospects' => $cart]);

        $invoice_data = [
            'number' => ((int) $this->Client_invoices_model->get_max_invoice_number()) + 1,
            'clientid' => $client_id,
            'date' => date('Y-m-d'),
            'duedate' => date('Y-m-d', strtotime('+4 days')),
            'subtotal' => $total,
            'total_tax' => 0,
            'total' => $total,
            'adjustment' => 0,
            'hash' => app_generate_hash(),
            'project_id' => '',
            'billing_street' => '',
            'billing_city' => '',
            'billing_state' => '',
            'billing_zip' => '',
            'show_shipping_on_invoice' => 'on',
            'shipping_street' => '',
            'shipping_city' => '',
            'shipping_state' => '',
            'shipping_zip' => '',
            'tags' => 'LeadEvo Campaign Checkout',
            'discount_total' => '0',
            'task_id' => '',
            'expense_id' => '',
            'clientnote' => '',
            'terms' => '',
            'discount_percent' => '0',
            'allowed_payment_modes' => ['stripe'],
            'currency' => 1,
            'recurring' => 0,
            'discount_type' => 0,
            'repeat_every_custom' => 1,
            'repeat_type_custom' => 'day',
            'adminnote' => '',
            'newitems' => [
                [
                    'description' => 'Campaign ' . $campaign_id,
                    'long_description' => $campaign_id . ' ' . $campaign['name'] . ' ' . $campaign['description'],
                    'rate' => $total,
                    'unit' => 0,
                    'order' => 1,
                    'qty' => 1,
                ]
            ]
        ];


        if (hooks()->apply_filters('validate_invoice_number', true)) {
            $number = ltrim($invoice_data['number'], '0');
            if (
                total_rows('invoices', [
                    'YEAR(date)' => (int) date('Y', strtotime(to_sql_date($invoice_data['date']))),
                    'number' => $number,
                    'status !=' => Invoices_model::STATUS_DRAFT,
                ])
            ) {
                set_alert('warning', _l('invoice_number_exists'));
                redirect(site_url('invoices/invoice'));
            }
        }

        $id = $this->Client_invoices_model->add($invoice_data);
        $invoice_data['id'] = $id;
        return $invoice_data;
        // if ($id) {
        //     echo json_encode(['status' => 'success', 'data' => $id]);
        //     $data = ['invoice_id' => $id];
        //     $this->Campaigns_model->update($client_id, $data);
        //     echo json_encode(['status' => 'success']);
        // }



    }

}
