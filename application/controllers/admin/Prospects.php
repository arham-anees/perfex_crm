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
    }

    public function index()
    {

        $data['prospects'] = $this->Prospects_model->get_all();
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
        redirect(admin_url('leadevo/prospects'));
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

    public function mark_as_fake()
    {
        $id = $this->input->post('id');
        if (isset($id)) {
            $this->Prospects_model->mark_fake($id);
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
        $data['title'] = 'Reported Prospects';
        // Load the view file with data
        $this->load->view('admin/leadevo/prospects/prospect_reported', $data);
    }

    public function send_to_campaign()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            echo json_encode(array('status' => 'success', 'message' => 'Prospect sent to desired campaign'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid Method'));
        }
    }

}
