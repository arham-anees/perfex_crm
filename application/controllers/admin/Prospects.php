<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospects extends AdminController
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
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Reported_Prospects_model');
    }

    public function index()
    {
        $filter = $this->input->get('filter');
        if ($filter) {
            $data['prospects'] = $this->Prospects_model->get_all($filter);
        } else {
            $data['prospects'] = $this->Prospects_model->get_all('');
        }
        $this->load->view('admin/leadevo/prospects/index', $data);

    }
    public function fake()
    {
        $data['prospects'] = $this->Prospects_model->get_all_fake();
        $this->load->view('admin/leadevo/prospects/fake', $data);
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
            redirect(admin_url('prospects'));
        }

        $data['prospect'] = $this->Prospects_model->get($id);
        $data['statuses'] = $this->Prospect_status_model->get_all();
        $data['types'] = $this->Prospect_types_model->get_all();
        $data['categories'] = $this->Prospect_categories_model->get_all();
        $data['acquisition_channels'] = $this->Acquisition_channels_model->get_all();
        $data['industries'] = $this->Industries_model->get_all();

        $this->load->view('admin/leadevo/prospects/edit', $data);
    }

    public function delete($id)
    {
        $this->Prospects_model->delete($id);
        redirect(admin_url('/prospects'));
    }

    public function view($id)
    {
        $data['prospect'] = $this->Prospects_model->get($id);

        // Load additional data if needed for displaying in the view
        $data['status'] = $this->Prospect_status_model->get($data['prospect']->status_id);
        $data['type'] = $this->Prospect_types_model->get($data['prospect']->type_id);
        $data['category'] = $this->Prospect_categories_model->get($data['prospect']->category_id);
        $data['acquisition_channel'] = $this->Acquisition_channels_model->get($data['prospect']->acquisition_channel_id);
        $data['industry'] = $this->Industries_model->get($data['prospect']->industry_id);

        $this->load->view('admin/leadevo/prospects/view', $data);
    }

    public function receive()
    {
        $view_data = [];
        if ($this->input->get()) {
            $data = $this->input->get();
            // validate data
            $has_error = false;
            $error_message = '';
            if (
                (!isset($data['first_name']) || $data['first_name'] == '') &&
                (!isset($data['last_name']) || $data['last_name'] == '') &&
                (!isset($data['email']) || $data['email'] == '') &&
                (!isset($data['client_id']) || $data['client_id'] == '') &&
                (!isset($data['phone']) || $data['phone'] == '')
            ) {
                $has_error = true;
                $error_message = 'Data is invalid';
            }
            if (!$has_error) {
                $data['is_active'] = 1;
                unset($data['id']);
                $this->Prospects_model->insert($data);
            } else {
                $view_data['error'] = $error_message;
            }
            $this->load->view('admin/leadevo/prospects/receive_post', $view_data);
        } else {
            $this->load->view('admin/leadevo/prospects/receive_get', $view_data);
        }
    }

    public function sold()
    {
        $data['prospects'] = $this->clients_model->get_sold();
        $this->load->view('admin/leadevo/prospects/sold', $data);
    }
    public function mark_as_fake()
    {
        $id = $this->input->post('id');
        $description = $this->input->post('fake_description');

        if (isset($id) && isset($description)) {
            $this->Prospects_model->mark_fake($id, $description);
        }
        redirect(admin_url('prospects'));
    }


    public function mark_as_auto_deliverable()
    {
        $id = $this->input->post('id');
        if (isset($id)) {
            $this->Prospects_model->mark_as_auto_deliverable($id);
        }
        redirect(admin_url('prospects'));
    }
    public function rate()
    {
        $id = $this->input->post('id');
        if (isset($id)) {
            $stars = $this->input->post('rating');
            $this->Prospects_model->rate($id, $stars);
        }
        redirect(admin_url('prospects'));
    }
    public function mark_as_available_sale()
    {
        $id = $this->input->post('id');
        $desired_amount = $this->input->post('desired_amount');
        $min_amount = $this->input->post('min_amount');
        $is_exclusive = (int) $this->input->post('deal');
        if (!isset($desired_amount) || !isset($min_amount) || !isset($is_exclusive) || !isset($id)) {
            echo json_encode(array('status' => 'error', 'message' => 'Please fill all the data'));
        } else {
            $this->Prospects_model->update_sale_status($id, 1, $is_exclusive, $desired_amount, $min_amount);
            echo json_encode(array('status' => 'success', 'message' => 'Prospect is available for sale now'));
        }
    }

    public function update_status()
    {
        $id = $this->input->post('id');
        $confirm_status = $this->input->post('confirm_status');

        if ($id && isset($confirm_status)) {
            $data = [
                'is_confirmed' => $confirm_status
            ];

            $update = $this->Prospects_model->update($id, $data);

            if ($update) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
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

        $this->load->view('admin/leadevo/prospects/prospect_reported', $data);
    }

    public function view_reported($id)
    {
        $this->load->model('leadevo/Reported_Prospects_model'); // Load the model
        $data['reported_prospect'] = $this->Reported_Prospects_model->get($id);

        if (!$data['reported_prospect']) {
            show_404(); // If no data found, show 404 page
        }
        $this->load->view('admin/leadevo/prospects/view_reported', $data);

    }



    public function send_to_campaign()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            try {
                $data = $this->input->post();
                $this->Campaigns_model->send_prospect($data['prospect_id'], $data['campaign_id']);
                echo json_encode(array('status' => 'success', 'message' => 'Prospect sent to desired campaign'));
            } catch (Exception $e) {
                echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid Method'));
        }
    }
    public function get_replacements()
    {

        try {
            $data = $this->input->get();
            $replacements = $this->Prospects_model->get_replacements($data['id']);
            echo json_encode(array('status' => 'success', 'data' => json_encode($replacements)));
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }

    }

    public function replace()
    {
        try {
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $new_prospect_id = $this->input->post('new_prospect_id');
                $old_prospect_id = $this->input->post('old_prospect_id');
                $campaign_id = $this->input->post('campaign_id');
                $this->Prospects_model->replace($old_prospect_id, $new_prospect_id, $campaign_id);
                echo json_encode(array('status' => 'success', 'message' => 'Prospect has been replaced'));

            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Method not allowed'));
            }
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    }
    public function make_call()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['phone'])) {
            $phoneNumber = $_POST['phone'];
            $apiKey = 'your_aircall_api_key';
            $endpoint = 'https://api.aircall.io/v1/calls';

            $data = [
                'to' => $phoneNumber,
                'user_id' => 'your_aircall_user_id',  // Optional: specify the Aircall user who will make the call
                'from' => 'your_aircall_number',     // Optional: specify the Aircall number to use
            ];

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json",
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpCode === 201) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }

    }

    public function get_prospect_data()
    {
        $id = $this->input->get('id');
        if ($id) {
            $prospect = $this->Prospects_model->get($id);

            $prospect->full_name = $prospect->first_name . ' ' . $prospect->last_name;
            $prospect->status = $this->Prospect_status_model->get($prospect->status_id)->name ?? 'Unknown';
            $prospect->type = $this->Prospect_types_model->get($prospect->type_id)->name ?? 'Unknown';
            $prospect->category = $this->Prospect_categories_model->get($prospect->category_id)->name ?? 'Unknown';
            $prospect->acquisition_channel = $this->Acquisition_channels_model->get($prospect->acquisition_channel_id)->name ?? 'Unknown';
            $prospect->industry = $this->Industries_model->get($prospect->industry_id)->name ?? 'Unknown';

            echo json_encode($prospect);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid prospect ID']);
        }
    }



}
