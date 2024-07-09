<?php defined('BASEPATH') or exit('No direct script access allowed');

class Statuses extends AdminController {

    public function __construct() {
        parent::__construct();
        // Load any necessary models, libraries, helpers, etc.
        $this->load->model('Appointments_status_model');
    }

    public function index() {
        // $data['subjects'] = $this.Appointments_status_model->get_all();
        $this->load->view('statuses');
    }

    public function delete() {
        // Check if the request method is POST
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Retrieve POST data
            $status_id = $this->input->post('status_id');

            // Validate the input data
            if (empty($status_id)) {
                // Redirect with an error if validation fails
                $this->session->set_flashdata('error', 'Invalid subject ID.');
                redirect('..');
            }

            // Process the data (e.g., delete from the database)
            $deleted = $this->Appointments_status_model->delete($status_id);

            // Redirect with a success or error message
            if ($deleted) {
                $this->session->set_flashdata('success', 'Subject deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete subject.');
            }

            redirect('/admin/appointly/statuses');
        } else {
            // Redirect with an error if the request method is not POST
            $this->session->set_flashdata('error', 'Invalid request method.');
            redirect('/admin/appointly/statuses');
        }
    }

    public function create() {
        // Check if the request method is POST
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Retrieve POST data
            $name = $this->input->post('name');

            // Validate the input data
            if (empty($name)) {
                // Redirect with an error if validation fails
                $this->session->set_flashdata('error', 'Name is required.');
                redirect('/admin/appointly/statuses');
            }

            // Process the data (e.g., insert into the database)
            $inserted = $this->Appointments_status_model->create($name, "");

            // Redirect with a success or error message
            if ($inserted) {
                $this->session->set_flashdata('success', 'Subject added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add subject.');
            }

            redirect('/admin/appointly/statuses');
        } else {
            // Redirect with an error if the request method is not POST
            $this->session->set_flashdata('error', 'Invalid request method.');
            redirect('/admin/appointly/statuses');
        }
    }
    public function json()
    {
        $statuses = get_statuses();
        $data = [];
        foreach ($statuses as $status) {
            $row = [];
            $row[] = $status['name'];
            $row[] = '<form method="POST" action="statuses/delete">
                        <input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '">
                        <input type="hidden" name="status_id" value="' . $status['id'] . '">
                        <button type="submit" class="btn-delete-status"><i class="fa fa-trash"></i></button>
                    </form>';
            $data[] = $row;
        }
        echo json_encode(['data' => $data]);
    }

}
